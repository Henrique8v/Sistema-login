-- 1. Preparar a tabela de comentários para o estilo "Instagram" (Respostas)
ALTER TABLE `comentarios` 
ADD COLUMN `parent_id` INT(11) DEFAULT NULL AFTER `post_id`,
ADD COLUMN `gif_url` TEXT DEFAULT NULL AFTER `comentario`,
ADD CONSTRAINT `fk_comentario_parent` FOREIGN KEY (`parent_id`) REFERENCES `comentarios`(`id_comentario`) ON DELETE CASCADE;

-- 2. Preparar a tabela de mensagens_privadas para o estilo "WhatsApp" (Citação)
ALTER TABLE `mensagens_privadas` 
ADD COLUMN `citacao_id` INT(11) DEFAULT NULL AFTER `destinatario_id`,
ADD COLUMN `gif_url` TEXT DEFAULT NULL AFTER `mensagem`,
ADD CONSTRAINT `fk_mensagem_citacao` FOREIGN KEY (`citacao_id`) REFERENCES `mensagens_privadas`(`id`) ON DELETE SET NULL;

-- 3. Criar uma tabela simples para gerenciar emojis personalizados se necessário (opcional, mas recomendado)
CREATE TABLE IF NOT EXISTS `emojis_custom` (
  `id_emoji` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL,
  `url_imagem` text NOT NULL,
  PRIMARY KEY (`id_emoji`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;