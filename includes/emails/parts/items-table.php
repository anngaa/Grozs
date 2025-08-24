<?php if (!empty($cart) && is_array($cart)) : ?>
<table cellspacing="0" cellpadding="10" border="1" style="line-height:1.2;text-align:left;border-color:#eaeaea;border-collapse:collapse;font-family:sans-serif;width:100%;">
    <thead>
        <tr>
            <th style="padding:10px;background:#fafafa;text-align:left;">Produkts</th>
            <th style="padding:10px;background:#fafafa;text-align:left;">Detaļas</th>
            <th style="padding:10px;background:#fafafa;text-align:left;">Sk.</th>
            <th style="padding:10px;background:#fafafa;text-align:left;">Cena</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $sum = 0.0; // kopējā summa (qty * cena)
        foreach ($cart as $item): 
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

            // Normalizējam cenu skaitļošanai (noņem valūtas simbolus/atstarpes, komatus uz punktiem)
            $rawPrice = (string)($item['price'] ?? '0');
            $normalized = str_replace(['€', ' ', '\t', '\n', '\r'], '', $rawPrice);
            $normalized = str_replace(',', '.', $normalized);
            $unitPrice = (float)preg_replace('/[^0-9.\-]/', '', $normalized);
            $lineTotal = $unitPrice * $qty;
            $sum += $lineTotal;
        ?>
        <tr>
            <td><?php echo $title; ?></td>
            <td><?php echo $details ? implode(', ', $details) : ''; ?></td>
            <td><?php echo $qty; ?></td>
            <td>€&nbsp;<?php echo $price; ?></td>
        </tr>
        <?php endforeach; ?>
        <?php
            $formattedSum = number_format($sum, 2, '.', '');
            if (substr($formattedSum, -3) === '.00') {
                $formattedSum = substr($formattedSum, 0, -3);
            }
        ?>
        <tr>
            <td colspan="3" style="text-align:left; font-weight:bold;">Kopā:</td>
            <td style="font-weight:bold;">€&nbsp;<?php echo $formattedSum; ?></td>
        </tr>
    </tbody>
</table>
<?php endif; ?>