<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ProfitResult;
use App\Models\ProfitsSummary;
use App\Services\CurrencyConverterApi;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FileProcessor extends Controller
{
    /**
     * Shows the upload form.
     *
     * @return ResponseInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showUploadForm(): ResponseInterface
    {
        return $this->render('uploadForm.html.twig', []);
    }

    /**
     * Processes the profit results csv file submission.
     *
     * @return ResponseInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function processForm(ServerRequestInterface $request): ResponseInterface
    {
        // get conversion currency code
        $conversionCurrencyCode = $request->getParsedBody()['profits_conversion'];

        // get profits file & confirm file type.
        $profitsDocumentName = $_FILES['profits_doc']['name'];
        $fileExtension = pathinfo($profitsDocumentName, PATHINFO_EXTENSION);

        if ($fileExtension === 'csv')
        {
            // organize & store csv file data
            $profitResults = [];

            // read the CSV document
            $csvFile = fopen($_FILES['profits_doc']['tmp_name'], 'r');

            // read csv and map header
            $csv = array_map('str_getcsv', file($_FILES['profits_doc']['tmp_name'],FILE_SKIP_EMPTY_LINES));
            $keys = array_shift($csv);

            // verify sku, cost, price, and quantity are within the csv document
            if (count(array_intersect($keys, ['sku', 'cost', 'price', 'qty'])) === 4)
            {
                // get currency conversion amount
                $conversionAmount = CurrencyConverterApi::getConversion($conversionCurrencyCode);

                // cycle through rows and assign them to their respective variables
                foreach ($csv as $lineData) {
                    $row = array_combine($keys, $lineData);

                    $profitResult = new ProfitResult(
                        $row['sku'],
                        (float)$row['cost'],
                        (float)$row['price'],
                        (int)$row['qty'],
                        $conversionAmount
                    );

                    // store temporary row into final output data
                    $profitResults[] = $profitResult;
                }

                /*
                 * Return a summary model of results.
                 */
                $profitsSummaryModel = new ProfitsSummary($profitResults, $conversionAmount);

                return $this->render('viewProfitResults.html.twig', [
                    'headers' => $keys,
                    'profitResults' => $profitResults,
                    'summaryResults' => $profitsSummaryModel,
                    'file' => pathinfo($profitsDocumentName, PATHINFO_FILENAME),
                    'conversionCurrencyCode' => $conversionCurrencyCode
                ]);
            }
            else
            {
                // return to the upload form with an error message: file does not contain required headers
                return $this->render('uploadForm.html.twig', [
                    'error' => 'Your profits file does not contain sku, cost, price, or qty.'
                ]);
            }
        }
        else
        {
            // return to the upload form with an error message.
            return $this->render('uploadForm.html.twig', [
                'error' => 'Please upload a .csv file.'
            ]);
        }
    }
}