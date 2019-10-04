<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\Api\Converter\fixtures;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Deprecated;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ListField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Tax\TaxDefinition;

class DeprecatedDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'deprecated';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new IntField('price', 'price'))->addFlags(new Deprecated('6.1.0', '6.2.0', 'prices')),
            new ListField('prices', 'prices', IntField::class),
            (new FkField('tax_id', 'taxId', TaxDefinition::class))->addFlags(new Deprecated('6.1.0', '6.2.0')),
            (new ManyToOneAssociationField('tax', 'tax_id', TaxDefinition::class))->addFlags(new Deprecated('6.1.0', '6.2.0')),
            new FkField('product_id', 'productId', ProductDefinition::class),
            new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class)
        ]);
    }
}
