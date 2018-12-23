#!/usr/bin/env bash

randname() {
    local -x LC_ALL=C
    tr -dc '[:lower:]' < /dev/urandom |
        dd count=1 bs=16 2>/dev/null
}

read owner group owner_id group_id < <(stat -c '%U %G %u %g' .)
if [[ $owner = UNKNOWN ]]; then
    owner=$(randname)
    if [[ $group = UNKNOWN ]]; then
        group=$owner
        addgroup --system --gid "$group_id" "$group"
    fi
    adduser --system --uid=$owner_id --gid=$group_id "$owner"
fi

chown -R $owner:$group .

export $owner
