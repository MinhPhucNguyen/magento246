<?php

namespace Tigren\HelloWorld\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
//        Chuẩn bị database trước khi tạo
        $installer->startSetup();

//      Khai báo tên bảng và kiểu cho các cột
        if (!$installer->tableExists('Tigren_topic')) {
            $table = $installer
                ->getConnection()->newTable($installer->getTable('Tigren_topic'))
                ->addColumn(
                    'topic_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [
                        'identity' => true,
                        'primary' => true,
                        'nullable' => false,
                        'unsigned' => true //not negative number
                    ]
                )
                ->addColumn(
                    'title',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Topic Title'
                )
                ->addColumn(
                    'content',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '2M',
                    [],
                    'Topic Content'
                )
                ->addColumn(
                    'creation_time',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                    ],
                    'Topic Create Time',
                )->setComment('Tigren Topic Table');

            //      Tạo bảng $table khai báo ở trên
            $setup->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('Tigren_topic'),
                $setup->getIdxName(
                    $installer->getTable('Tigren_topic'),
                    ['topic_id','title','content','creation_time'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['topic_id','title','content','creation_time'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}