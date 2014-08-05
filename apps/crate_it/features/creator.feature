@javascript
Feature: Search, add and remove grant number 
  As a user
  I want to add grant number related to the crate
  
  Background:
    Given I have no crates
    And I have crate "crate1"
    And I'm logged in to ownCloud as "test"
    And I go to the crate_it page
    And I select crate "crate1"
    
  #CRATEIT-80
  Scenario: Creator lookup
    Given I fill in "keyword_creator" with "John"
    And I click the search creator button
    Then I should see these entries in the creator result list
      | name            | email               |
      | Prof John Smith | john@smith.com      |
      | Mr John Doe     | john@doe.org        |
      | Mr Daniel Johns | dan@silverchair.com |
      
  #CRATEIT-80
  Scenario: Add and Remove creators
    Given I fill in "keyword_creator" with "John"
    And I click the search creator button
    And I add creator "1" to the list
    And I add creator "3" to the list
    Then I should see these entries in the selected creatora list
      | name            | email               |
      | Prof John Smith | john@smith.com      |
      | Mr Daniel Johns | dan@silverchair.com |
    And I remove creator "john@smith.com" from the selected list
    Then I should see these entries in the selected creatora list
      | name            | email               |
      | Mr Daniel Johns | dan@silverchair.com |
    And I add creator "2" to the list
    Then I should see these entries in the selected creatora list
      | name            | email               |
      | Mr Daniel Johns | dan@silverchair.com |
      | Mr John Doe     | john@doe.org        |
      
   #CRATEIT-80
   Scenario: Creator lookup result should exclude selected numbers
     Given I fill in "keyword_creator" with "John"
     And I click the search creator button 
     And I add creator "2" to the list
     And I click the search creator button
     Then I should see these entries in the creator result list
      | name            | email               |
      | Prof John Smith | john@smith.com      |
      | Mr Daniel Johns | dan@silverchair.com |
     
   #CRATEIT-80
   Scenario: Server returns no results should trigger a notification
     Given I fill in "keyword_creator" with "John"
     When I click the search creator button and get no results
     Then I should see "0 new results returned"
     
   #CRATEIT-80
   Scenario: Click on 'Clear All' should remove all selected creators
     Given I fill in "keyword_creator" with "John"
     And I click the search creator button 
     And I add creator "1" to the list
     And I add creator "3" to the list
     When I clear all creators
     Then I should see "Clear all creators?"
     When I press "Clear" on the popup dialog
     Then I should have no selected creators
     
   #CRATEIT-80
   Scenario: A user can cancel clearing all creators
     Given I fill in "keyword_creator" with "John"
     And I click the search creator button 
     And I add creator "1" to the list
     And I add creator "3" to the list
     When I clear all creators
     Then I should see "Clear all creators?"
     When I press "Cancel" on the popup dialog
     Then I should see these entries in the selected creatora list
      | name            | email               |
      | Prof John Smith | john@smith.com      |
      | Mr Daniel Johns | dan@silverchair.com |
      
    #CRATEIT-177
    Scenario: A user can manually add a creator
      When I click on "add-creator"
      Then I fill in the following:
        | add-creator-name  | Joe Bloggs     |
        | add-creator-email | joe@bloggs.org |
      When I press "Add" on the popup dialog
      Then I should see these entries in the selected creatora list
        | name       | email          |
        | Joe Bloggs | joe@bloggs.org |

    #CRATEIT-177
    Scenario: A user can cancel manually adding a creator
      When I click on "add-creator"
      Then I fill in the following:
        | add-creator-name  | Joe Bloggs     |
        | add-creator-email | joe@bloggs.org |
      When I press "Add" on the popup dialog
      When I click on "add-creator"
      Then I fill in the following:
        | add-creator-name  | Elvis               |
        | add-creator-email | elvis@graceland.org |
      When I press "Cancel" on the popup dialog
      Then I should see these entries in the selected creatora list
        | name       | email          |
        | Joe Bloggs | joe@bloggs.org |
    
    #CRATEIT-177
    Scenario: A user can remove a manually added creator
      When I click on "add-creator"
      And I fill in the following:
        | add-creator-name  | Joe Bloggs     |
        | add-creator-email | joe@bloggs.org |
      Then I press "Add" on the popup dialog
      When I click on "add-creator"
      And I fill in the following:
        | add-creator-name  | Elvis               |
        | add-creator-email | elvis@graceland.org |
      Then I press "Add" on the popup dialog
      Then I should see these entries in the selected creatora list
        | name       | email               |
        | Joe Bloggs | joe@bloggs.org      |   
        | Elvis      | elvis@graceland.org |
      When I remove creator "elvis@graceland.org" from the selected list
      Then I should see these entries in the selected creatora list
        | name       | email               |
        | Joe Bloggs | joe@bloggs.org      |

      #CRATEIT-177
      Scenario: A manually added creator name is mandatory
        When I click on "add-creator"
        And I fill in "add-creator-name" with "  "
        Then I should see "Name is required"
        And the "Add" button in the popup dialog should be disabled

      #CRATEIT-177
      Scenario: A manually added creator name has a maximum length of 256 characters
        When I click on "add-creator"
        And I fill in "add-creator-name" with a long string of 257 characters
        Then I should see "Name must be less than 256 characters"
        And the "Add" button in the popup dialog should be disabled

      #CRATEIT-177
      Scenario: A manually added creator email is optional
        When I click on "add-creator"
        And I fill in "add-creator-name" with "Elvis"
        And I fill in "add-creator-email" with "  "
        Then I press "Add" on the popup dialog
        Then I should see these entries in the selected creatora list
        | name  | email |
        | Elvis |       |

      #CRATEIT-177
      Scenario: A manually added creator email has a maximum length of 128 characters
        When I click on "add-creator"
        And I fill in "add-creator-name" with "Elvis"
        And I fill in "add-creator-email" with a long string of 129 characters
        Then I should see "Email must be less than 128 characters"
        And the "Add" button in the popup dialog should be disabled

      #CRATEIT-177
      Scenario: A manually added creator email must be a valid email address
        When I click on "add-creator"
        And I fill in "add-creator-name" with "Elvis"
        And I fill in "add-creator-email" with "elvis"
        Then I should see "Must be a valid email address"
        And the "Add" button in the popup dialog should be disabled
        And I fill in "add-creator-email" with "elvis@graceland.org"
        Then I press "Add" on the popup dialog
        Then I should see these entries in the selected creatora list
        | name       | email               |
        | Elvis      | elvis@graceland.org |

