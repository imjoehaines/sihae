Feature: Archive page
  As a vistor
  I want to be able to see a list of all blog posts organised by year
  So that I can quickly browse all of the posts in the blog

  @database @createUser
  Scenario: Visiting a Sihae blog with multiple pages of blog posts
    Given there are some posts:
      | title                   | body                | date_created        |
      | Penny's Perfect Post    | Penny's post's text | 2016-05-05 00:00:00 |
      | Penny's Alright Post    | Another post's text | 2015-04-04 00:00:00 |
      | Penny's Premium Post    | Some more text      | 2014-03-03 00:00:00 |
      | Penny's P-repetive Post | Yet more text       | 2013-02-02 00:00:00 |
      | Penny pls stop          | And more text       | 2012-01-01 00:00:00 |
    And I am on "/archive"
    Then I should see "2016 Penny's Perfect Post"
    And I should see "2015 Penny's Alright Post"
    And I should see "2014 Penny's Premium Post"
    And I should see "2013 Penny's P-repetive Post"
    And I should see "2012 Penny pls stop"
