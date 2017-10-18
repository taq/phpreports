<?php

function mysqli_field_type($result, $field_offset) {
    static $types;
    $type_id = mysqli_fetch_field_direct($result, $field_offset)->type;
    if (!isset($types)) {
        $types = array();
        $constants = get_defined_constants(true);
        foreach ($constants['mysqli'] as $c => $n) if (preg_match('/^MYSQLI_TYPE_(.*)/', $c, $m)) $types[$n] = $m[1];
    }
     return array_key_exists($type_id, $types) ? $types[$type_id] : NULL;
}

?>
