// generate hak akses
INSERT INTO `sso_role_access` (`role_id`,`module_id`,`menu_id`,`feature_id`,`feature_name`,`status`)
SELECT 2, a.module_id, b.menu_id, c.feature_id, c.name, 1 FROM modules a
LEFT JOIN module_menu b ON a.module_id = b.module_id AND b.status = 1
LEFT JOIN module_feature c ON b.menu_id = c.menu_id AND b.status = 1
WHERE a.status = 1
