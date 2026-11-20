<?php

function search_orders($filters) {
    return null;
}

function get_order($id) {
    return null;
}

// Retrieve all shipping brackets
function get_shipping_brackets(PDO $pdo): array {
    $stmt = $pdo->query(
        'SELECT
            BracketID AS bracket_id,
            MinWeight AS min_weight,
            MaxWeight AS max_weight,
            Charge AS charge
        FROM BRACKETS
        ORDER BY MinWeight ASC'
    );

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Save updated shipping brackets
function save_shipping_brackets(PDO $pdo, $brackets): bool {
    try {
        // Start transaction
        $pdo->beginTransaction();
        $stmt = $pdo->prepare(
            'UPDATE BRACKETS
            SET MinWeight = :min_weight,
                MaxWeight = :max_weight,
                Charge = :charge
            WHERE BracketID = :bracket_id'
        );
        foreach ($brackets as $bracket) {
            $stmt->execute([
                ':min_weight' => $bracket['min_weight'],
                ':max_weight' =>
                    ($bracket['max_weight'] === '' ||
                     $bracket['max_weight'] === null)
                    ? null
                    : $bracket['max_weight'],
                ':charge' => $bracket['charge'],
                ':bracket_id' => $bracket['bracket_id']
            ]);
        }
        // Save all changes
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        // Undo changes if something fails
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log($e->getMessage());
        return false;
    }
}
?>
