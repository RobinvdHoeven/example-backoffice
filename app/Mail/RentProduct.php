<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use App\Models\Translation;


class RentProduct extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        App::setLocale($this->locale);
        $thanks = __trans('Robin Backoffice - Uw order is geaccepteerd', 'email');
//		$message = Translation::where('category', 'anders')->get();
//		if(app()->getLocale() == 'nl') {
//			$thanks = $message[1]->text_nl;
//		} else if (app()->getLocale() == 'fr') {
//			$thanks = $message[1]->text_fr;
//		} else if (app()->getLocale() == 'de') {
//			$thanks = $message[1]->text_de;
//		} else {
//			$thanks = $message[1]->text_en;
//		}

        return $this->from('info@robinbackoffice.com')->view('mail.template')->subject($thanks)->with(['data' => $this->data]);


    }
}
