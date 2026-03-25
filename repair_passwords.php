<?php
$admins = \App\Models\Admin::all();
$count = 0;
foreach ($admins as $admin) {
    if (!str_starts_with($admin->password, '$2')) {
        $admin->password = \Illuminate\Support\Facades\Hash::make($admin->password);
        $admin->save(['timestamps' => false]);
        echo "Hashed password for " . $admin->email . "\n";
        $count++;
    }
}
if ($count === 0) {
    echo "No plaintext passwords found to hash.\n";
}
