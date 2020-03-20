<?php

namespace NextcloudApiWrapper;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GroupFoldersClient extends AbstractClient
{
    const   FOLDER_PART = 'v2.php/apps/groupfolders/folders';
    /**
     * CRUDS permissions.
     * @since 8.0.0
     */
    const PERMISSION_CREATE = 4;
    const PERMISSION_READ = 1;
    const PERMISSION_UPDATE = 2;
    const PERMISSION_DELETE = 8;
    const PERMISSION_SHARE = 16;
    const PERMISSION_ALL = 31;

    /**
     * Adds a GroupFolder
     * @param $username
     * @param $password
     * @return NextcloudResponse
     * @throws NCException
     * @throws TransportExceptionInterface
     */
    public function addFolder($mountpoint)
    {

        return $this->connection->submitRequest(
            Connection::POST,
            self::FOLDER_PART,
            [
                'mountpoint' => $mountpoint,
            ]
        );
    }

    /**
     * Gets a list of GroupFolders
     * @param array $params
     * @return NextcloudResponse
     * @throws NCException
     * @throws TransportExceptionInterface
     */
    public function listFolders(array $params = [])
    {

        $params = $this->resolve(
            $params,
            function (OptionsResolver $resolver) {
                $resolver->setDefaults(
                    [
                        'search',
                        'limit',
                        'offset',
                    ]
                );
            }
        );

        return $this->connection->request(Connection::GET, self::FOLDER_PART.$this->buildUriParams($params));
    }

    /**
     * Gets data about a given Folder
     * @param $folderId
     * @return NextcloudResponse
     * @throws NCException
     * @throws TransportExceptionInterface
     */
    public function getFolder($folderId)
    {
        return $this->connection->request(Connection::GET, self::FOLDER_PART.'/'.$folderId);
    }

    /**
     * Updates a Mountpoint for an Folder
     * @param $folderId
     * @param $mountPoint
     * @return NextcloudResponse
     * @throws NCException
     * @throws TransportExceptionInterface
     */
    public function editMountpoint($folderId, $mountPoint)
    {

        return $this->connection->submitRequest(
            Connection::PUT,
            self::FOLDER_PART.'/'.$folderId.'/mountpoint',
            [
                'mountpoint' => $mountPoint,
            ]
        );
    }

    /**
     * Deletes a folder
     * @param $folderId
     * @return NextcloudResponse
     * @throws NCException
     * @throws TransportExceptionInterface
     */
    public function deleteFolder($folderId)
    {

        return $this->connection->request(Connection::DELETE, self::FOLDER_PART.'/'.$folderId);
    }

    /**
     * Adds a group to a folder
     * @param $folderId
     * @param $groupId
     * @return NextcloudResponse
     * @throws NCException
     * @throws TransportExceptionInterface
     */
    public function addGroupToFolder($folderId, $groupId)
    {

        return $this->connection->submitRequest(
            Connection::POST,
            self::FOLDER_PART.'/'.$folderId.'/groups',
            [
                'group' => $groupId,
            ]
        );
    }

    /**
     * Remove a group from a folder
     * @param $folderId
     * @param $groupId
     * @return NextcloudResponse
     * @throws NCException
     * @throws TransportExceptionInterface
     */
    public function removeGroupFromFolder($folderId, $groupId)
    {

        return $this->connection->submitRequest(
            Connection::DELETE,
            self::FOLDER_PART.'/'.$folderId.'/groups/'.$groupId,
            []
        );
    }

    /**
     * Sett Permissions a group has on a Folder
     * @param $folderId
     * @param $groupId
     * @param $permissions
     * @return NextcloudResponse
     * @throws NCException
     * @throws TransportExceptionInterface
     */
    public function setFolderGroupPermissions($folderId, $groupId, $permissions)
    {

        return $this->connection->submitRequest(
            Connection::POST,
            self::FOLDER_PART.'/'.$folderId.'/groups/'.$groupId,
            [
                'permissions' => $permissions,
            ]
        );
    }

    /**
     * Sett Permissions a group has on a Folder
     * @param $folderId
     * @param $quota
     * @return NextcloudResponse
     * @throws NCException
     * @throws TransportExceptionInterface
     */
    public function setFolderQuota($folderId, $quota)
    {

        return $this->connection->submitRequest(
            Connection::POST,
            self::FOLDER_PART.'/'.$folderId.'/quota',
            [
                'quota' => $quota,
            ]
        );
    }
}