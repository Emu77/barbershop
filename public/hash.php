<?php
echo '<pre>';
echo 'Admin:       ' . password_hash('Admin1234!',    PASSWORD_BCRYPT, ['cost'=>12]) . "\n";
echo 'Mitarbeiter: ' . password_hash('Mitarbeiter1!', PASSWORD_BCRYPT, ['cost'=>12]) . "\n";
echo 'Kunde:       ' . password_hash('Kunde1234!',    PASSWORD_BCRYPT, ['cost'=>12]) . "\n";
echo '</pre>';