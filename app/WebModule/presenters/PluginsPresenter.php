<?php

declare(strict_types=1);

namespace WebModule\Presenters;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use WebModule\Forms\QrCodeForm;

class PluginsPresenter extends WebModulePresenter
{
    private const SecretLength = 20;

    /**
     * @throws Exception
     */
    public function renderOtpLogin(): void
    {
        $secret = random_bytes(self::SecretLength);
        $session = $this->getSession("OTP");
        $issuer = $session->get("issuer") ?: "AdminNeo";
        $username = $session->get("username") ?: "root";

        $uri = "otpauth://totp/" . rawurlencode($issuer) . ":" . rawurlencode($username) .
            "?secret=" . $this->encodeBase32($secret) .
            "&issuer=" . rawurlencode($issuer);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($uri)
            ->errorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->size(492)
            ->margin(0)
            ->build();

        $this->getTemplate()->encodedSecret = base64_encode($secret);
        $this->getTemplate()->qrCodeData = $result->getDataUri();
    }

    // https://php.vrana.cz/jednorazove-heslo.php
    private function encodeBase32(string $data): string
    {
        $codes = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";

        $bits = "";
        foreach (str_split($data) as $c) {
            $bits .= sprintf("%08b", ord($c));
        }

        $result = "";
        foreach (str_split($bits, 5) as $c) {
            $result .= $codes[bindec($c)];
        }

        return $result;
    }

    public function createComponentQrCodeForm(string $name): QrCodeForm
    {
        $form = new QrCodeForm($this, $name);
        $form->onSuccess[] = [$this, "onQrCodeFormSubmitted"];

        if (!$form->isSubmitted()) {
            $session = $this->getSession("OTP");
            $form->setDefaults([
                "issuer" => $session->get("issuer") ?: "AdminNeo",
                "username" => $session->get("username") ?: "root",
            ]);
        }

        return $form;
    }

    public function onQrCodeFormSubmitted(QrCodeForm $form): never
    {
        $values = $form->getValues();

        $session = $this->getSession("OTP");
        $session->setExpiration("+10 minutes");

        $session->set("issuer", $values->issuer);
        $session->set("username", $values->username);

        $this->redirect("this#qr-code");
    }
}
