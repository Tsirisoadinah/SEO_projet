INSERT INTO Categorie (libelle) VALUES
('Breaking'),
('Humanitaire'),
('Économie'),
('Société'),
('Analyse'),
('Chronologie'),
('Reportage');

INSERT INTO Journaliste (Nom, date_embauche, Actif, image) VALUES
('Rédaction internationale', '2022-01-15', true, 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&q=80'),
('Pierre Dumont', '2021-06-01', true, 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80'),
('Marie Fontaine', '2023-03-10', true, 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80'),
('Ahmed Benali', '2020-09-20', true, 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80'),
('Sophie Laurent', '2024-01-05', true, 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80');

INSERT INTO articles (Titre, Introduction, Contenu, image, alt, creation, Id_Categorie) VALUES

('Derniers développements sur le front : situation critique dans le nord',
'Les observateurs internationaux font état de mouvements significatifs.',
'Les observateurs internationaux font état de mouvements de troupes significatifs près de la frontière nord. L''ONU appelle à une désescalade immédiate.',
'https://images.unsplash.com/photo-1589998059171-988d887df646?w=800&q=80',
'Situation sur le front nord du conflit',
'2026-03-30 09:14:00',
1),

('Les négociations à Genève reprennent après une semaine de suspension',
'Les délégations se retrouvent sous médiation des Nations Unies.',
'Les délégations se retrouvent autour de la table sous médiation des Nations Unies. Les positions restent éloignées mais le dialogue est rétabli.',
'https://images.unsplash.com/photo-1616469829167-0bd76a80c913?w=800&q=80',
'Négociations diplomatiques à Genève',
'2026-03-30 07:50:00',
2),

('Crise humanitaire : plus de 2 millions de déplacés internes',
'Les camps de réfugiés débordent de capacité.',
'Les camps de réfugiés débordent de capacité. Les ONG alertent sur le manque de ressources médicales.',
'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=800&q=80',
'Camp de réfugiés déplacés',
'2026-03-29 18:30:00',
3),

('Sanctions économiques : impact sur la population',
'L''économie locale subit de plein fouet.',
'Les sanctions économiques ont un impact direct sur la vie quotidienne des civils.',
'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=800&q=80',
'Impact économique',
'2026-03-29 14:00:00',
4),

('Téhéran : la vie quotidienne sous tension',
'Les habitants tentent de maintenir une normalité.',
'Malgré les tensions, les habitants poursuivent leur quotidien.',
'https://images.unsplash.com/photo-1565711561500-49678a10a63f?w=800&q=80',
'Vie quotidienne à Téhéran',
'2026-03-29 10:00:00',
5),

('Analyse : les enjeux géopolitiques régionaux',
'Le conflit redessine les alliances.',
'Les experts estiment que ce conflit dépasse les frontières iraniennes.',
'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&q=80',
'Carte géopolitique',
'2026-03-28 16:00:00',
6),

('Chronologie complète du conflit',
'Retour sur les événements clés.',
'Une analyse détaillée de l''évolution du conflit.',
'https://images.unsplash.com/photo-1504711434969-e33886168d5c?w=800&q=80',
'Frise chronologique',
'2026-03-28 12:00:00',
7),

('Témoignages de civils',
'Paroles de familles touchées.',
'Des témoignages poignants sur la vie en zone de conflit.',
'https://images.unsplash.com/photo-1532375810709-75b1da00537c?w=800&q=80',
'Témoignage civil',
'2026-03-27 09:00:00',
8);

INSERT INTO journaliste_article (Id_articles, Id_Journaliste) VALUES
(4, 1),
(5, 2),
(6, 3),
(7, 4),
(8, 2),
(9, 5),
(10, 1),
(11, 3);