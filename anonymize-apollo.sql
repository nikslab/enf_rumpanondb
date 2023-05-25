--
-- p_inquire
--
UPDATE entity_company SET
  `phone` = "+1 212 5551212",
  `fax` = "+1 212 5551212",
  `email` = "anon@enfsolar.com",
  `email_panel` = "anon@enfsolar.com",
  `email_inverter` = "anon@enfsolar.com",
  `email_mounting_system` = "anon@enfsolar.com",
  `email_cell` = "anon@enfsolar.com",
  `email_eva` = "anon@enfsolar.com",
  `email_backsheet` = "anon@enfsolar.com",
  `social_weibo` = NULL,
  `social_linkedin` = NULL,
  `social_facebook` = NULL,
  `social_twitter` = NULL,
  `address` = "1 Park St, New York, NY",
  `website` = "https://www.enfsolar.com",
  `x` = "36.0601649",
  `y` = "-80.2277113",
  `linkedin` = "-1",
  `url_name` = "url-name";


--
-- p_inquire
--
UPDATE p_inquire SET
    `name` = 'Bob Jonhson',
    `company_name` = 'FBI Solar Inc.',
    `phone` = '+1 212 5551212',
    `email` = 'anon@enfsolar.com',
    `skype` = NULL,
    `qq` = NULL,
    `comments` = 'enquiry text',
    `comments_checked` = 'enquiry text';

