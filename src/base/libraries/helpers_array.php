<?php

namespace base\libraries;

function trimArray($data) {
    if ($data == null)
        return null;

    if (is_array($data)) {
        return array_map('\base\trimArray', $data);
    } else
        return trim($data);
}
