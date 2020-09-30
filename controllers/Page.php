<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';


class Page extends CI_Controller {

	function __construct()
  {
    parent::__construct();

    /* memanggil model untuk ditampilkan pada masing2 modul */
		
		$this->load->model('Cart_model');
    $this->load->model('Company_model');
		$this->load->model('Featured_model');
		$this->load->model('Kategori_model');
		$this->load->model('Kontak_model');
    $this->load->model('Produk_model');

    /* memanggil function dari masing2 model yang akan digunakan */
    
    $this->data['company_data'] 			= $this->Company_model->get_by_company();
    $this->data['featured_data'] 			= $this->Featured_model->get_all_front();
    $this->data['kategori_data'] 			= $this->Kategori_model->get_all();
		$this->data['kontak'] 						= $this->Kontak_model->get_all();
		$this->data['total_cart_navbar'] 	= $this->Cart_model->total_cart_navbar();
  } 

	public function company()
	{
		$this->data['title'] 							= 'Profil Toko';

    /* melakukan pengecekan data, apabila ada maka akan ditampilkan */
  	$this->data['company']            = $this->Company_model->get_by_company();

    /* memanggil view yang telah disiapkan dan passing data dari model ke view*/
		$this->load->view('front/company/body', $this->data);
	}


	public function konfirmasi_pembayaran()
	{
		$this->data['title'] 							= 'Konfirmasi Pembayaran';

		$this->load->view('front/page/konfirmasi_pembayaran', $this->data);
	}

	public function konfirmasi_kirim()
	{
		if (isset($_POST['submit'])) {
			// Instantiation and passing `true` enables exceptions
			$mail = new PHPMailer(true);

			$no_invoice         = $this->input->post('no_invoice');
			$nama_pengirim      = $this->input->post('nama_pengirim');
			$email              = $this->input->post('email');
			$jumlah         = $this->input->post('jumlah');
			$bank_asal      = $this->input->post('bank_asal');
			$bank_tujuan              = $this->input->post('bank_tujuan');

			//Server settings
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
			$mail->isSMTP();                                            // Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
			$mail->Username   = 'yunitarisandi99@gmail.com';                     // SMTP username
			$mail->Password   = 'yunitacepay';                               // SMTP password
			// $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

			//Recipients
			$mail->setFrom('yunitarisandi99@gmail.com');
			$mail->addAddress($email, $nama_pengirim);     // Add a recipient

			$mail->addReplyTo('yunitarisandi99@gmail.com');
			// $mail->addCC('cc@example.com');
			// $mail->addBCC('bcc@example.com');

			// Attachments
			// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = 'Konfirmasi Pembayaran dari Localhost dengan Codeigniter';
			$mail->Body    = '<h1>Halo, Admin.</h1> <p>Ada pesanan baru dengan rincian sebagai berikut : <br> No Invoice: ' . $no_invoice . '
								<br> Nama Pengirim :'. $nama_pengirim. '<br> Email :' .$email. ' <br> Jumlah :' .$jumlah. '<br> Bank Asal :' .$bank_asal. ' <br> Bank Tujuan :'.$bank_tujuan .' <h3>Terimakasih Admin. Tolong Segera Kirim Barangnya yaaa!</h3>';

			if ($mail->send()) {
				echo 'Konfirmasi pembayaran telah berhasil';
			} else {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		} else {
			echo "Tekan dulu tombolnya bos";
		}
	}
}
