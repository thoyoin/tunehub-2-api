<?php

declare(strict_types = 1);

namespace App\Infrastructure\ArgumentResolver;

use App\Infrastructure\Request\Merch\MerchVariantRequest;
use App\Infrastructure\Request\Merch\UploadMerchRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class UploadMerchRequestResolver implements ValueResolverInterface
{
    public function __construct(
        private ValidatorInterface $validator,
    )
    {}

    /**
     * @return iterable<UploadMerchRequest>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== UploadMerchRequest::class) {
            return [];
        }

        $description = $request->request->get('item_description');

        $itemTitle = (string) $request->request->get('item_title', '');
        $itemDescription = is_string($description) && $description !== ''
            ? $description
            : null;

        $images = $request->files->get('images');
        $imagesArray = is_array($images) ? $images : ($images !== null ? [$images] : null);

        $rawVariantsJson = (string) $request->request->get('merch_variants', '[]');

        try {
            $rawVariants = json_decode($rawVariantsJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new BadRequestHttpException('Invalid JSON in merch_variants');
        }

        $merchVariants = [];

        if (is_array($rawVariants)) {
            foreach ($rawVariants as $variant) {
                /** @var array<string, mixed> $variant */

                $variantName = isset($variant['variant_name']) && is_scalar($variant['variant_name'])
                    ? (string) $variant['variant_name']
                    : '';

                $price = isset($variant['price']) && is_numeric($variant['price'])
                    ? (float) $variant['price']
                    : 0.0;

                $stock = isset($variant['stock']) && is_numeric($variant['stock'])
                    ? (int) $variant['stock']
                    : 0;

                $merchVariants[] = new MerchVariantRequest(
                    variantName: $variantName,
                    price: $price,
                    stock: $stock
                );
            }
        }

        /** @var array<UploadedFile>|null $imagesArray */

        $dtoRequest = new UploadMerchRequest(
            $itemTitle,
            $itemDescription,
            $imagesArray,
            $merchVariants
        );

        $violations = $this->validator->validate($dtoRequest);

        if (count($violations) > 0) {
            throw new BadRequestHttpException((string) $violations->get(0)->getMessage());
        }

        yield $dtoRequest;
    }
}
