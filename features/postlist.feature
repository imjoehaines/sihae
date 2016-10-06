Feature: Blog Post List
  As a Sihae user
  I want to be able to see a list of my blog posts
  So that users can browse my blog

  @database
  Scenario: Visiting a Sihae blog without any posts
    Given I am on the homepage
    Then I should see "There aren't any posts!"

  @database
  Scenario: Visiting a Sihae blog with a single blog post
    Given there is a post:
      | title                | body                |
      | Penny's Perfect Post | Penny's post's text |
    And I am on the homepage
    Then I should see "Penny's Perfect Post"
    And I should see "Penny's post's text"
    And I should see text matching "[pP]osted (\d*) second(s?) ago"
    And I should see "Newer Posts"
    And I should see text matching "Page (\d*) of (\d*)"
    And I should see "Older Posts"

  @database
  Scenario: Visiting a Sihae blog with multiple blog posts
    Given there are some posts:
      | title                | body                |
      | Penny's Perfect Post | Penny's post's text |
      | Penny's Alright Post | Another post's text |
      | Penny's Premium Post | Some more text      |
    And I am on the homepage
    Then I should see "Penny's Perfect Post"
    And I should see "Penny's post's text"
    And I should see "Penny's Alright Post"
    And I should see "Another post's text"
    And I should see "Penny's Premium Post"
    And I should see "Some more text"

  @database
  Scenario: Visiting a Sihae blog with multiple pages of blog posts
    Given there are some posts:
      | title                   | body                |
      | Penny's Perfect Post    | Penny's post's text |
      | Penny's Alright Post    | Another post's text |
      | Penny's Premium Post    | Some more text      |
      | Penny's P-repetive Post | Yet more text       |
      | Penny pls stop          | And more text       |
    And I am on the homepage
    Then I should see "Penny's Perfect Post"
    And I should see "Penny's Alright Post"
    And I should see "Penny's Premium Post"
    And I should see "Penny's P-repetive Post"
    And I should see "Newer Posts"
    And I should see "Page 1 of 2"
    And I should see "Older Posts"
    But I should not see "Penny pls stop"
