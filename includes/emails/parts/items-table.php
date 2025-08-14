<?php if (!empty($cart) && is_array($cart)) : ?>
<table cellspacing="0" cellpadding="10" border="1" style="line-height:0.9;border-color:#eaeaea;border-collapse:collapse;font-family:sans-serif;width:100%;">
    <thead>
        <tr>
            <th style="padding:10px;background:#fafafa;text-align:left;">Nosaukums</th>
            <th style="padding:10px;background:#fafafa;text-align:left;">Detaļas</th>
            <th style="padding:10px;background:#fafafa;text-align:left;">Daudzums</th>
            <th style="padding:10px;background:#fafafa;text-align:left;">Cena</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cart as $item): 
            $details = [];

            if (!empty($item['krasa']))           $details[] = esc_html($item['krasa']);
            if (!empty($item['izmers']))          $details[] = esc_html($item['izmers']);
            if (!empty($item['produkta_izmers'])) $details[] = esc_html($item['produkta_izmers']);
            if (!empty($item['materials']))       $details[] = esc_html($item['materials']);

            // “Vēlos” loģika
            if (!empty($item['atvilknes']) && trim((string)$item['atvilknes']) === 'Vēlos') {
                $details[] = 'Atvilknes zem gultas';
            }
            if (!empty($item['pacelams']) && trim((string)$item['pacelams']) === 'Vēlos') {
                $details[] = 'Paceļams matracis';
            }

            $qty   = max(1, (int)($item['quantity'] ?? 1));
            $title = isset($item['title']) ? esc_html($item['title']) : '';
            $price = isset($item['price']) ? esc_html($item['price']) : '';
        ?>
        <tr>
            <td><?php echo $title; ?></td>
            <td><?php echo $details ? implode(', ', $details) : ''; ?></td>
            <td><?php echo $qty; ?></td>
            <td>€ <?php echo $price; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>