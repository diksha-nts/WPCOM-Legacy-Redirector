Feature: Test that the Find Domains subcommand works correctly.

  Background:
    Given a WP installation with the WPCOM Legacy Redirector plugin

  Scenario: WPCOM Legacy Redirector finds zero domains by default
    When I run `wp wpcom-legacy-redirector find-domains`
    Then STDOUT should contain:
      """
      Found 0 unique outbound domains
      """
