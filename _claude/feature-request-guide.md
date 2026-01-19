# Feature Request Guide

> This guide is for stakeholders who want to submit new feature requests.

---

## How It Works

1. **Describe the need** to Claude Chat (use the prompt below)
2. **Claude generates** a complete feature request
3. **Copy the text** and paste into a new task/ticket
4. **Team lead validates** and prioritizes

---

## Prompt for Claude Chat

Copy this text and start a new Claude Chat session:

```
You are a product assistant for [PROJECT_NAME]. Your job is to help me formulate feature requests.

When I describe a wish or need, ask follow-up questions to understand:
1. What should the user be able to do?
2. Why is this important?
3. Who is the user? (define relevant user roles for your project)
4. What is "must have" vs "nice to have"?
5. How do we know it works?

When you have enough info, generate a complete feature request in this format:

---

# [Title]

**Date:** [YYYY-MM-DD]
**From:** [Name]

---

## What is needed?
[Description of desired functionality]

## Why?
[Business value / user benefit]

## User Story
As a [role], I want to [action], so that [benefit].

## Scope
**Must have:**
- [Requirement 1]
- [Requirement 2]

**Nice to have:**
- [Addition 1]

## Acceptance Criteria
- [ ] [Criterion 1]
- [ ] [Criterion 2]
- [ ] [Criterion 3]

## Links
- [Relevant links if applicable, otherwise remove this section]

---

Be specific and avoid vague formulations. Acceptance criteria should be testable.
```

---

## Example of Completed Request

Here's an example of what a filled-out feature request looks like:

---

# Export Data as CSV

**Date:** 2026-01-13
**From:** Team Member

---

## What is needed?
Add an "Export to CSV" button on the dashboard that lets users download their data as a CSV file for use in spreadsheets or other tools.

## Why?
Users need to analyze their data outside the application. Having an easy export option will increase user satisfaction and make the platform more useful.

## User Story
As a user, I want to export my data as CSV, so that I can analyze it in Excel or other tools.

## Scope
**Must have:**
- Export button visible on dashboard
- CSV includes all relevant data fields
- Proper encoding for special characters

**Nice to have:**
- Option to select date range
- Option to choose which fields to include

## Acceptance Criteria
- [ ] "Export to CSV" button visible on dashboard
- [ ] Clicking button downloads a .csv file
- [ ] File opens correctly in Excel/Google Sheets
- [ ] All data fields are properly formatted

---

## Tips for Good Requests

**Be specific:**
- Bad: "Make it faster"
- Good: "Reduce page load time to under 2 seconds"

**Think about the user:**
- Bad: "We need a database for X"
- Good: "As a user, I want to see my history"

**Testable criteria:**
- Bad: "It should work well"
- Good: "User can click 'Download' and receives a .csv file within 5 seconds"

---

## Project-Specific Context

When writing requests, keep in mind:

### User Roles
| Role | Description |
|------|-------------|
| **User** | [Define: Standard user of the system] |
| **Admin** | [Define: Administrator with elevated privileges] |
| **[Other Role]** | [Define: Other relevant roles] |

### Key Features
- [List main features/modules of your project]
- [Include URL patterns if relevant]
- [Include integration points]

### Technical Notes
- [Add project-specific technical context]
- [Include API endpoints, data structures, etc.]

---

## Contact

Questions about this process? Contact the project lead.
