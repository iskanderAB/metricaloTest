<?php

namespace App\Repository;

use App\Domain\Card\Card;
use App\Domain\Card\CardId;
use App\Domain\Payment\Storage\PaymentRepositoryInterface;
use App\Entity\Payment;
use App\Repository\CardRepository;
use App\Service\CardTransformer;
use App\Service\PaymentTransformer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 */
class PaymentRepository extends ServiceEntityRepository implements PaymentRepositoryInterface
{

    public function __construct(
        private PaymentTransformer $paymentTransformer,
        ManagerRegistry            $registry, private readonly CardRepository $cardRepository, private readonly CardTransformer $cardTransformer
    )
    {
        parent::__construct($registry, Payment::class);
    }


//    /**
//     * @return Payment[] Returns an array of Payment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Payment
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function insert(\App\Domain\Payment\Payment $paymentDomain): void
    {

        $card = $this->cardRepository->findOneBy([
            'cardNumber' => $paymentDomain->getCard()->getCardNumber()
        ]);
        if (!$card) {
            $paymentDomain->getCard()->setId(CardId::new());
            $card = $this->cardTransformer->toEntity($paymentDomain->getCard());
            $this->getEntityManager()->persist($card);
        }

        $payment = $this->paymentTransformer->toEntity($paymentDomain);
        $payment->setCard($card);

        $this->getEntityManager()->persist($payment);
        $this->getEntityManager()->flush();
    }
}
