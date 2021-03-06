<?php
/**
 * Copyright (c) Enalean, 2017. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tuleap\Svn\Repository;

class HookConfigUpdator
{
    /**
     * @var HookDao
     */
    private $hook_dao;
    /**
     * @var \ProjectHistoryDao
     */
    private $project_history_dao;
    /**
     * @var HookConfigChecker
     */
    private $hook_config_checker;

    public function __construct(
        HookDao $hook_dao,
        \ProjectHistoryDao $project_history_dao,
        HookConfigChecker $hook_config_checker
    ) {
        $this->hook_dao            = $hook_dao;
        $this->project_history_dao = $project_history_dao;
        $this->hook_config_checker = $hook_config_checker;
    }

    public function updateHookConfig(Repository $repository, array $hook_config)
    {
        if ($this->hook_config_checker->hasConfigurationChanged($repository, $hook_config)) {
            $this->project_history_dao->groupAddHistory(
                'svn_multi_repository_hook_update',
                $this->extractOldValues($repository, $hook_config),
                $repository->getProject()->getID()
            );

            $this->hook_dao->updateHookConfig(
                $repository->getId(),
                HookConfig::sanitizeHookConfigArray($hook_config)
            );
        }
    }

    private function extractOldValues(Repository $repository, array $hook_config)
    {
        return $repository->getName() .
            PHP_EOL .
            HookConfig::MANDATORY_REFERENCE . ": " .
            $this->extractHookReadableValue($hook_config, HookConfig::MANDATORY_REFERENCE) .
            PHP_EOL .
            HookConfig::COMMIT_MESSAGE_CAN_CHANGE . ": " .
            $this->extractHookReadableValue($hook_config, HookConfig::COMMIT_MESSAGE_CAN_CHANGE);
    }

    private function extractHookReadableValue($value, $index)
    {
        if (isset($value[$index])) {
            return var_export($value[$index], true);
        }

        return '-';
    }
}
