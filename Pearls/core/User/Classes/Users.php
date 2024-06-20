<?php

namespace Pearls\User\Classes;

use Pearls\User\Models\User;

class Users
{
    /**
     * Users constructor.
     */
    function __construct()
    {
    }

    public function getUsersCounts()
    {
        $counts = [];

        $counts['total'] = User::count();
        $counts['admin'] = $this->getUsersCountBy('type', 'admin');
        $counts['employee'] = $this->getUsersCountBy('type', 'employee');
        $counts['driver'] = $this->getUsersCountBy('type', 'driver');
        $counts['pending'] = $this->getUsersCountBy('status', 'pending');

        return $counts;
    }

    /**
     * @param $column
     * @param $value
     * @param array $conditions e.g. ['status'=>'active']
     * @return int
     */
    public function getUsersCountBy($column, $value, $conditions = [])
    {
        $users = User::query();

        $users->where($column, $value);

        if (!empty($conditions)) {
            $users->where($conditions);
        }

        return $users->count();
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function getUsersList($type = 'employee')
    {
        $users = \DB::table('users')->select(\DB::raw("concat(last_name,', ',first_name) as full_name"), 'id');

        if ($type != 'all') {
            $users = $users->where('type', $type);
        }

        $users = $users->pluck('full_name', 'id');

        return $users;
    }

    public function getTeamMembersList($team_id)
    {
        $members = User::select(\DB::raw("concat(last_name,', ',first_name) as full_name"), 'id')
            ->where('type', 'employee')
            ->with('trouble_tickets')
            ->whereHas('teams', function ($query) use ($team_id) {
                $query->where('teams.id', $team_id);
            })
            ->get();

        $sortedMembers = $members->sortBy(function ($member) {
            return $member->trouble_tickets->count();
        })->pluck('full_name', 'id');

        return $sortedMembers;
    }

}
