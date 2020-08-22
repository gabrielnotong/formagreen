<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\TrainingCenter;
use App\Entity\UserLambda;
use Endroid\QrCode\Factory\QrCodeFactoryInterface;

class QRCodeGenerator
{
    private QrCodeFactoryInterface $qrCodeFactory;

    public function __construct(QrCodeFactoryInterface $qrCodeFactory) {
        $this->qrCodeFactory = $qrCodeFactory;
    }

    public function forUserLambda(UserLambda $member): string
    {
        $qrCode = $this->qrCodeFactory->create(sprintf(
            UserLambda::QRCODE_CONTENT,
            $member->__toString(),
            $member->getEmail(),
            $member->getStartsAt()->format('Y-m-d'),
            $member->getEndsAt()->format('Y-m-d'),
            $member->getPhoneNumber()
        ));

        return $qrCode->getText();
    }

    public function forTrainingCenter(TrainingCenter $member): string
    {
        $qrCode = $this->qrCodeFactory->create(sprintf(
            TrainingCenter::QRCODE_CONTENT,
            $member->getCompanyName(),
            $member->getEmail(),
            $member->getStartsAt()->format('Y-m-d'),
            $member->getEndsAt()->format('Y-m-d'),
            $member->getAddress(),
            $member->getPhoneNumber()
        ));

        return $qrCode->getText();
    }
}
