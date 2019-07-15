<?php declare(strict_types=1);

namespace Swag\PlatformDemoData\DataProvider;

use Doctrine\DBAL\FetchMode;

class ShippingMethodProvider extends DemoDataProvider
{
    public function getPriority(): int
    {
        return 200;
    }

    public function getAction(): string
    {
        return 'upsert';
    }

    public function getEntity(): string
    {
        return 'shipping_method';
    }

    public function getPayload(): array
    {
        $ruleId = $this->getRuleId();
        $payload = [];
        foreach ($this->getShippingMethodIds() as $shippingMethodId) {
            $payload[] = [
                'id' => $shippingMethodId,
                'availabilityRuleId' => $ruleId
            ];
        }

        return $payload;
    }

    private function getShippingMethodIds(): array
    {
        return $this->connection->executeQuery('
            SELECT LOWER(HEX(`id`))
            FROM `shipping_method`;
        ')->fetchAll(FetchMode::COLUMN);
    }

    private function getRuleId(): string
    {
        $result = $this->connection->fetchColumn('
            SELECT LOWER(HEX(`id`))
            FROM `rule`
        ');

        if (!$result) {
            throw new \RuntimeException('No country for iso code "' . $iso . '" found, please make sure that basic data is available by running the migrations.');
        }

        return (string) $result;
    }
}