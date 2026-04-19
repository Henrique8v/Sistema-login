CREATE TABLE IF NOT EXISTS mapa_site (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    descricao VARCHAR(255)
);

INSERT INTO mapa_site (titulo, url, descricao) VALUES 
('Galeria de Fotos', 'site/site/public/galeria.php', 'Veja as imagens da ordem'),
('O Princípio', 'site/site/public/o_principio.php', 'A história e os fundamentos'),
('Membros', 'membros.php', 'Busque por outros corvos'),
('Timeline', 'site/site/public/index.php', 'Feed de atualizações');