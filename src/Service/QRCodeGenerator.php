<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Endroid\QrCode\Factory\QrCodeFactoryInterface;

class QRCodeGenerator
{
    private QrCodeFactoryInterface $qrCodeFactory;

    public function __construct(QrCodeFactoryInterface $qrCodeFactory) {
        $this->qrCodeFactory = $qrCodeFactory;
    }

    public function forUser(User $member): string
    {
        $qrCode = $this->qrCodeFactory->create(sprintf(
            User::QRCODE_CONTENT,
            $member->__toString(),
            $member->getEmail(),
            $member->getStartsAt()->format('Y-m-d'),
            $member->getEndsAt()->format('Y-m-d'),
            $member->getAddress(),
            $member->getPhoneNumber()
        ));

        return $qrCode->getText();
    }
}
