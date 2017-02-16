Feature: Edit a Blog Post
  As a Sihae user
  I want to be able to edit a blog post
  So that I can fix spelling errors and update posts

  @database @loginAdmin
  Scenario: Editing a post
    Given there is a post:
      | title                | body                | date_created        |
      | Penny's Perfect Post | Penny's post's text | 2016-01-01 00:00:00 |
    And I am on "post/pennys-perfect-post"
    And I should see "Penny's Perfect Post"
    And I should see "Penny's post's text"
    And I should see "1st Jan 2016"
    When I follow "Edit this post"
    And I fill in "title" with "Penny's Perfecter Post"
    And I fill in "body" with "Different post text"
    And I press "submit"
    Then I should be on "post/pennys-perfect-post"
    And I should see "Penny's Perfecter Post"
    And I should see "Different post text"

  @database @loginAdmin
  Scenario: Editing a post but failing validation
    Given there is a post:
      | title                | body                | date_created        |
      | Penny's Perfect Post | Penny's post's text | 2016-01-01 00:00:00 |
    And I am on "post/pennys-perfect-post"
    And I should see "Penny's Perfect Post"
    And I should see "Penny's post's text"
    And I should see "1st Jan 2016"
    When I follow "Edit this post"
    And I fill in "title" with "a"
    And I fill in "body" with "b"
    And I press "submit"
    Then I should be on "post/edit/pennys-perfect-post"
    And I should see "Oops! Please fix the following errors"
    And I should see "Title: not at least 3 characters"
    And I should see "Body: not at least 10 characters"
