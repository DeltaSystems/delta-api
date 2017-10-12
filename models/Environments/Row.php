<?php

namespace Model\Environments;

use Dewdrop\Db\Row as DewdropRow;

class Row extends DewdropRow
{
    public function getApiResponse()
    {
        $resources = $this->getTable()->getAdapter()->fetchAll(
            'SELECT t.name, t.title, r.encrypted_contents, r.encrypted_key
            FROM environment_resources r 
            JOIN environment_resource_types t ON r.environment_resource_type_id = t.environment_resource_type_id
            WHERE r.environment_id = ?
            ORDER BY r.environment_resource_id',
            [$this->get('environment_id')]
        );

        return [
            'name'      => $this->get('name'),
            'resources' => $resources
        ];
    }

    public function addResource($resourceTypeId, $value, $publicKey)
    {
        openssl_seal(
            json_encode($value),
            $cipherText,
            $encryptedKeys,
            [$publicKey],
            'RC4'
        );

        $this->getTable()->getAdapter()->insert(
            'environment_resources',
            [
                'environment_id'               => $this->get('environment_id'),
                'environment_resource_type_id' => $resourceTypeId,
                'encrypted_key'                => bin2hex($encryptedKeys[0]),
                'encrypted_contents'           => bin2hex($cipherText),
                'cipher'                       => 'RC4'
            ]
        );

        return $this;
    }
}