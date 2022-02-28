<?php

/**
 * Rename this file to local-config.php (or production-config.php)
 */
define('DB_NAME', 'dbname');
define('DB_USER', 'dbuser');
define('DB_PASSWORD', 'dbpass');
define('DB_HOST', 'dbhost');
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
// See https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'x89E_nbf|r(_y+o&]{;VfzfCWm!*(PxTl|m7:U2WoU+~|E{yGYmiEqLFm^b-6AeE');
define('SECURE_AUTH_KEY',  'C-}er~ {|z^hj6h_>;BHDC?2ec?4}|=K.]aO2[Nvq,EBy|YVmy_=u/JFexyRPFqT');
define('LOGGED_IN_KEY',    'LGqA1&QThK:7a^YT^)g]+y$Kmd[ql(J|73xnNs_Eq98tx2C(8_8C2w=Pb^ch!=cz');
define('NONCE_KEY',        '+G{!k!2)MBvTxUT4i{#TU/=>C1#R)}-@xA~ <J_VWJ;|wgh+T<?{Wmwl}LP/xY|)');
define('AUTH_SALT',        'y6()`OIc[o/J)zIi70 R9|xJ3AQL@#9j|->o|/18Rt,0Cd0e2pps%Mn`6/WR-}90');
define('SECURE_AUTH_SALT', ',]<_=Q:$P_H_`-yYt`,qT3zWYA;l,@T~Jm5 i~Zv(!6tM/NO4Z!_Ea:{cb$%D-@C');
define('LOGGED_IN_SALT',   'V[c-vxF9S1XP_01Ugss+ak|>G bf#^H[G(P9n?nxPP#VV#;&AzBy8BB%h*6zx}q-');
define('NONCE_SALT',       'v|N8n|Ux*ky_?nV:Gb?u>+I;AJzj/@Pfuqca)6r/5j#u9)&$q.J|*Ja&4G$Q8MRN');

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
  $_SERVER['HTTPS'] = 'on';
}
