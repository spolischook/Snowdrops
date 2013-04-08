Feature: referral
  In order to invite referral users
  As a user
  I need to be able to give other users referral link

  Scenario: View homepage like uregister user
    Given I am on homepage
    Then I should see "Sign up today"
    When I follow "Sign up today"
    Then I should see "Password:"
    And I should see "Verification:"

  Scenario: Registration user Sergey Polischook
    Given I am on "register/"
    When I fill in "fos_user_registration_form_firstName" with "Sergey"
    And I fill in "fos_user_registration_form_lastName" with "Polischook"
    And I fill in "fos_user_registration_form_email" with "SPolischook@gmail.com"
    And I fill in "fos_user_registration_form_plainPassword_first" with "123456"
    And I fill in "fos_user_registration_form_plainPassword_second" with "123456"
    And I press "Register"
    Then I should see "your account is now activated"
#    And I wait 10 seconds
    And I should see "Hello, Sergey"
    And I should not see "Sign up today"
    Then I am on "logout"

  Scenario: Registration user Bob Dylan
    Given I am follow "SPolischook@gmail.com" referral link
    When I follow "Sign up today"
    And I fill in "fos_user_registration_form_firstName" with "Bob"
    And I fill in "fos_user_registration_form_lastName" with "Dylan"
    And I fill in "fos_user_registration_form_email" with "BDylan@gmail.com"
    And I fill in "fos_user_registration_form_plainPassword_first" with "123456"
    And I fill in "fos_user_registration_form_plainPassword_second" with "123456"
    And I press "Register"
    Then I should see "your account is now activated"
    And I should see "Hello, Bob"
    And I should not see "Sign up today"
    And "BDylan@gmail.com" should be referral for "SPolischook@gmail.com"
