<?php
/*********************************************************************************
 * This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
 * and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
 * You can contact us at info@spicecrm.io
 * 
 * SpiceCRM is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * 
 * SpiceCRM is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ********************************************************************************/

namespace SpiceCRM\includes\authentication\ZeroShot;

use SpiceCRM\modules\Users\User;
use SpiceCRM\data\SpiceBean;

/**
 * Represents an access request for zero-shot evaluation
 */
class AccessRequest
{
    /**
     * @var User The user making the request
     */
    public $user;

    /**
     * @var SpiceBean|string The resource being accessed (bean or module name)
     */
    public $resource;

    /**
     * @var string The action being performed (view, edit, delete, export, etc.)
     */
    public $action;

    /**
     * @var array User attributes for ABAC evaluation
     */
    public $userAttributes;

    /**
     * @var array Resource attributes for ABAC evaluation
     */
    public $resourceAttributes;

    /**
     * @var array Context attributes (time, location, device, etc.)
     */
    public $contextAttributes;

    /**
     * @var string Unique request ID for tracking
     */
    public $requestId;

    /**
     * @var float Timestamp when request was created
     */
    public $timestamp;

    /**
     * Constructor
     * 
     * @param User $user
     * @param mixed $resource
     * @param string $action
     * @param array $contextAttributes
     */
    public function __construct(User $user, $resource, string $action, array $contextAttributes = [])
    {
        $this->user = $user;
        $this->resource = $resource;
        $this->action = $action;
        $this->contextAttributes = $contextAttributes;
        $this->requestId = $this->generateRequestId();
        $this->timestamp = microtime(true);
        
        // Extract attributes
        $this->userAttributes = $this->extractUserAttributes($user);
        $this->resourceAttributes = $this->extractResourceAttributes($resource);
    }

    /**
     * Generate unique request ID
     * 
     * @return string
     */
    private function generateRequestId(): string
    {
        return uniqid('zs_', true);
    }

    /**
     * Extract user attributes for ABAC
     * 
     * @param User $user
     * @return array
     */
    private function extractUserAttributes(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->user_name,
            'is_admin' => $user->is_admin,
            'department' => $user->department ?? null,
            'title' => $user->title ?? null,
            'employee_status' => $user->employee_status ?? null,
            'portal_only' => $user->portal_only ?? false,
            'is_api_user' => $user->is_api_user ?? false,
        ];
    }

    /**
     * Extract resource attributes for ABAC
     * 
     * @param mixed $resource
     * @return array
     */
    private function extractResourceAttributes($resource): array
    {
        if ($resource instanceof SpiceBean) {
            return [
                'module' => $resource->_module,
                'id' => $resource->id ?? null,
                'assigned_user_id' => $resource->assigned_user_id ?? null,
                'created_by' => $resource->created_by ?? null,
                'deleted' => $resource->deleted ?? false,
            ];
        } elseif (is_string($resource)) {
            return [
                'module' => $resource,
            ];
        }
        
        return [];
    }

    /**
     * Convert request to array for logging/processing
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'requestId' => $this->requestId,
            'timestamp' => $this->timestamp,
            'user' => $this->userAttributes,
            'resource' => $this->resourceAttributes,
            'action' => $this->action,
            'context' => $this->contextAttributes,
        ];
    }
}
