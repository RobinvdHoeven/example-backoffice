<?php

namespace App\Models;

use App\Library\InvoicePdf;
use Illuminate\Database\Eloquent\Model;
use PDF;

class Invoice extends Model
{

    protected $dates = [
        'created_at',
        'updated_at',
        'date',
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function createPdf()
    {

        $pdf = new InvoicePdf();

        if (!is_null($this->file)) {
            return;
        }

        $fileName = 'app/invoices/' . $this->number . '.pdf';

        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        // set document information

        $pdf->SetAuthor('Van Essen Catering en Events');
        $pdf->SetTitle('Factuur ' . $this->number);
        $pdf->SetSubject('Factuur ' . $this->number);
        $pdf->SetKeywords('Van Essen, Asperges, zeghetmetasperges.nl, Factuur');

        $pdf->SetMargins(PDF_MARGIN_LEFT, '15', PDF_MARGIN_RIGHT);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, '55');

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);


        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 14, '');

        $pdf->SetLineStyle(['width' => 0.5, 'color' => [0, 152, 216]]);


        $invoiceData = '<p style="font-family:helvetica,sans-serif;font-size:14px;">';
        if ($this->order->is_company == 1) {
            $invoiceData .= $this->order->company_name . '<br>';
        }

        $invoiceData .=
            $this->order->firstname . ' ' . $this->order->lastname . '<br>
' . $this->order->street . ' ' . $this->order->housenr . '<br>
' . $this->order->postcode . ' ' . $this->order->city . '</p>';


        $pdf->writeHTMLCell(0, 0, 125, 50, $invoiceData, 0, 1, 0, true, '', true);

        $html = '

<p style="font-family:helvetica,sans-serif;font-size:14px;">
Van Essen Catering & Events B.V.<br>
Stettinweg 5<br>
3825 PL Amersfoort<br>
Telefoon 033 - 4654142<br>
E-mail info@zeghetmetasperges.nl<br>
Bank NL 18 RABO 0300 9768 60<br>
<small style="font-size:10px;line-height:9px;">De rekening staat op naam van Backbone Marketing B.V.<br>Ten name van Van Essen Catering.</small><br>
</p>

<p style="font-family:helvetica,sans-serif;font-size:14px;"><br><br><br><br>
		Factuurdatum: 		' . $this->date->format('d-m-Y') . '<br>
		Factuurnummer: 		' . $this->number . '<br>
		Leverdatum: ' . $this->order->delivery_date->format('d-m-Y') . '<br><br></p><br>

			<table cellspacing="0" cellpadding="0" border="0" style="line-height:14px;">
				<tr style="font-size:12px;font-weight:bold;">
					<td width="55%">Omschrijving</td>
					<td width="15%" align="center">Aantal</td>
					<td width="15%" align="center">Prijs excl.</td>
					<td width="15%" align="right">Bedrag excl.</td>
				</tr>
				<tr>
					<td colspan="4"><br><hr></td>
				</tr>
        ';

		$cnt=0;
        
        foreach ($this->order->getLines() as $line) {
			$cnt++;
			
			if ($line['vatRate']==9) {
				$unit_price_without_btw = $line['unitPrice']['value'] / 1.09;
			} else {
				$unit_price_without_btw = $line['unitPrice']['value'] / 1.21;
			}

            $line_price_without_btw = $line['totalAmount']['value'] - $line['vatAmount']['value'];

			if ($cnt == 1) {
	            $html .= '
					<tr style="font-size:13px;line-height:13px;">
						<td><br>' . $line['name'] . '</td>
						<td align="center"><br>' . $line['quantity'] . '</td>
						<td align="center"><br>€ ' . number_format($unit_price_without_btw, 2, ',', '.') . '</td>
						<td align="right"><br>€ ' . number_format($line_price_without_btw, 2, ',', '.') . '</td>
					</tr>
	            ';				
			} else {
	            $html .= '
					<tr style="font-size:13px;line-height:13px;">
						<td><br><br>' . $line['name'] . '</td>
						<td align="center"><br><br>' . $line['quantity'] . '</td>
						<td align="center"><br><br>€ ' . number_format($unit_price_without_btw, 2, ',', '.') . '</td>
						<td align="right"><br><br>€ ' . number_format($line_price_without_btw, 2, ',', '.') . '</td>
					</tr>
	            ';					
			}

        }

        $html .= '
                <tr style="font-size:13px;line-height:13px;">
					<td colspan="4"><br><hr></td>
				</tr>

				<tr style="font-size:13px;line-height:13px;">
					<td colspan="3">Subtotaal exclusief BTW</td>
					<td align="right">€ ' . number_format($this->subtotal_amount, 2, ',', '.') . '</td>
				</tr>


                <tr style="font-size:13px;line-height:13px;">
					<td colspan="4">&nbsp;</td>
				</tr>

				<tr style="font-size:13px;line-height:13px;">
					<td colspan="3">BTW</td>
					<td align="right">€ ' . number_format($this->vat_amount, 2, ',', '.') . '</td>
				</tr>

				<tr style="font-size:13px;line-height:13px;">
					<td colspan="4"><br><hr></td>
				</tr>

				<tr style="font-size:13px;line-height:13px;font-weight:bold;">
					<td colspan="3"><b>Totaal</b></td>
					<td align="right"><b>€ ' . number_format($this->total_amount, 2, ',', '.') . '</b></td>
				</tr>

			</table><br><br><br><br><br>

			<strong style="font-size:15px;">Betaling is reeds voldaan via iDEAL.</strong><br><br>



		';
        $pdf->writeHTMLCell(0, 0, '', 50, $html, 0, 1, 0, true, '', true);
        $pdf->Output(storage_path($fileName), 'F');

        $this->file = $fileName;
        $this->save();

        return true;
    }
}
