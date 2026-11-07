<?php

function search_orders($filters) {
    return null;
}

function get_order($id) {
    return null;
}

function get_shipping_brackets(PDO $pdo): array {
    $stmt = $pdo->query(
        'SELECT bracket_id, min_weight, max_weight, charge 
        FROM shipping_brackets ORDER BY min_weight ASC'
    );
    return $stmt->fetchAll();
}

function save_shipping_brackets(PDO $pdo, $brackets) {
    try {
        foreach ($brackets as $bracket) {
            $stmt = $pdo->prepare(
                'UPDATE shipping_brackets 
                SET min_weight = :min_weight, max_weight = :max_weight, charge = :charge 
                WHERE bracket_id = :bracket_id'
            );
            $stmt->execute([
                ':min_weight' => $bracket['min_weight'],
                ':max_weight' => $bracket['max_weight'],
                ':charge' => $bracket['charge'],
                ':bracket_id' => $bracket['bracket_id']
            ]);
        }
        return true;
    } catch (PDOException $e) {
        echo 'Failed to save shipping brackets: ' . $e->getMessage();
        return false;
    }
}