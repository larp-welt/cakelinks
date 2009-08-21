SELECT links . *
FROM links
LEFT JOIN links_tags ON links.id = links_tags.link_id
WHERE links_tags.link_id IS NULL ;