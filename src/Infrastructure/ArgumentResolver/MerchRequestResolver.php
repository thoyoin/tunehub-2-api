<?php

declare(strict_types = 1);

namespace App\Infrastructure\ArgumentResolver;

use App\Infrastructure\Request\Merch\MerchVariantRequest;
use App\Infrastructure\Request\Merch\UpdateMerchRequest;
use App\Infrastructure\Request\Merch\UpdateMerchVariantRequest;
use App\Infrastructure\Request\Merch\UploadMerchRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class MerchRequestResolver implements ValueResolverInterface
{
    public function __construct(
        private ValidatorInterface $validator,
    )
    {}

    /**
     * @return iterable<UploadMerchRequest|UpdateMerchRequest>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!in_array($argument->getType(), [UploadMerchRequest::class, UpdateMerchRequest::class], true)) {
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
            $rawVariants = json_decode(
                $rawVariantsJson,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            if (!is_array($rawVariants)) {
                throw new BadRequestHttpException('Invalid merch_variants');
            }

            /** @var array<int, array<string, mixed>> $rawVariants */
        } catch (\JsonException) {
            throw new BadRequestHttpException('Invalid JSON in merch_variants');
        }

        /** @var array<UploadedFile>|null $imagesArray */

        $dtoRequest = match ($argument->getType()) {
            UploadMerchRequest::class => $this->buildUploadRequest($itemTitle, $itemDescription, $imagesArray, $rawVariants),
            UpdateMerchRequest::class => $this->buildUpdateRequest($request, $itemTitle, $itemDescription, $imagesArray, $rawVariants),
        };

        $violations = $this->validator->validate($dtoRequest);

        if (count($violations) > 0) {
            throw new BadRequestHttpException((string) $violations->get(0)->getMessage());
        }

        yield $dtoRequest;
    }

    /**
     * @param array<UploadedFile>|null $images
     * @param array<int,array<string,mixed>> $rawVariants
     */
    private function buildUploadRequest(
        string $title,
        ?string $description,
        ?array $images,
        array $rawVariants
    ): UploadMerchRequest
    {
        $variants = [];

        /** @var array<string,mixed> $variant */
        foreach ($rawVariants as $variant) {
            $title = is_string($variant['title'] ?? null)
                ? $variant['title']
                : '';

            $price = is_numeric($variant['price'] ?? null)
                ? (float) $variant['price']
                : 0.0;

            $stock = is_numeric($variant['stock'] ?? null)
                ? (int) $variant['stock']
                : 0;

            $variants[] = new MerchVariantRequest(
                variantName: $title,
                price: $price,
                stock: $stock
            );
        }

        return new UploadMerchRequest($title, $description, $images, $variants);
    }

    /**
     * @param array<UploadedFile>|null $images
     * @param array<int,array<string,mixed>> $rawVariants
     */
    private function buildUpdateRequest(
        Request $request,
        string $title,
        ?string $description,
        ?array $images,
        array $rawVariants
    ): UpdateMerchRequest
    {
        $productId = ($request->request->get('id') ?? $request->attributes->get('id') ?? '');

        if (!is_string($productId) || $productId === '') {
            throw new BadRequestHttpException('Invalid id');
        }

        $variants = [];

        foreach ($rawVariants as $variant) {
            $variantName = is_string($variant['title'] ?? null)
                ? $variant['title']
                : null;

            $price = is_int($variant['price'] ?? null)
                ? $variant['price']
                : null;

            $stock = is_int($variant['stock'] ?? null)
                ? $variant['stock']
                : null;

            $id = is_string($variant['id'] ?? null)
                ? $variant['id']
                : null;

            $variants[] = new UpdateMerchVariantRequest(
                variantName: $variantName,
                price: $price,
                stock: $stock,
                id: $id
            );
        }

        return new UpdateMerchRequest(
            id: $productId,
            itemTitle: $request->request->has('item_title') ? $title : null,
            itemDescription: $request->request->has('item_description') ? $description : null,
            images: $images,
            merchVariants: $request->request->has('merch_variants') ? $variants : null,
        );
    }
}
