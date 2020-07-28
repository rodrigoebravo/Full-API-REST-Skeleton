CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `tipo` int(11) NOT NULL,
  `clave` varchar(250) COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);
  ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;