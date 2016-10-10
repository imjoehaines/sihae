Feature: Blog Post List
  As a Sihae user
  I want to be able to see a list of my blog posts
  So that users can browse my blog

  @database
  Scenario: Visiting a Sihae blog without any posts
    Given I am on the homepage
    Then I should see "There aren't any posts!"

  @database @createUser
  Scenario: Visiting a Sihae blog with a single blog post
    Given there is a post:
      | title                | body                |
      | Penny's Perfect Post | Penny's post's text |
    And I am on the homepage
    Then I should see "Penny's Perfect Post"

  @database @createUser
  Scenario: Visiting a Sihae blog with multiple blog posts
    Given there are some posts:
      | title                | body                |
      | Penny's Perfect Post | Penny's post's text |
      | Penny's Alright Post | Another post's text |
      | Penny's Premium Post | Some more text      |
    And I am on the homepage
    Then I should see "Penny's Perfect Post"
    And I should see "Penny's Alright Post"
    And I should see "Penny's Premium Post"

  @database @createUser
  Scenario: Visiting a Sihae blog with multiple pages of blog posts
    Given there are some posts:
      | title                    | body                | date_created        |
      | Penny's Perfect Post     | Penny's post's text | 2016-01-05 00:00:00 |
      | Penny's Alright Post     | Another post's text | 2016-01-04 00:00:00 |
      | Penny's Premium Post     | Some more text      | 2016-01-03 00:00:00 |
      | Penny's Prepetive Post 1 | Yet more text       | 2016-01-02 00:00:00 |
      | Penny's Prepetive Post 2 | Yet more text       | 2016-01-02 00:00:00 |
      | Penny's Prepetive Post 3 | Yet more text       | 2016-01-02 00:00:00 |
      | Penny's Prepetive Post 4 | Yet more text       | 2016-01-02 00:00:00 |
      | Penny's Prepetive Post 5 | Yet more text       | 2016-01-02 00:00:00 |
      | Penny pls stop           | And more text       | 2016-01-01 00:00:00 |
    And I am on the homepage
    Then I should see "Penny's Perfect Post"
    And I should see "Penny's Alright Post"
    And I should see "Penny's Premium Post"
    And I should see "Penny's Prepetive Post 1"
    And I should see "Penny's Prepetive Post 2"
    And I should see "Penny's Prepetive Post 3"
    And I should see "Penny's Prepetive Post 4"
    And I should see "Penny's Prepetive Post 5"
    And I should see "Newer Posts"
    And I should see "Page 1 of 2"
    And I should see "Older Posts"
    But I should not see "Penny pls stop"
