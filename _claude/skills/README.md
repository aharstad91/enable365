# Skills Directory

This folder contains **skill files** that give Claude specific expertise and personas when triggered with `@skillname`.

## How It Works

When you type `@design` in a prompt, the hook in `.claude/settings.local.json` automatically loads `skills/designer.md`, giving Claude the context to act as a designer.

## Available Skills

| Trigger | File | Description |
|---------|------|-------------|
| `@design` | `designer.md` | UI/UX design expertise |
| `@backend` | `backend.md` | Server-side architecture |
| `@frontend` | `frontend.md` | Modern web development |
| `@review` | `reviewer.md` | Code review best practices |

## Creating New Skills

1. Create a new `.md` file in this folder
2. Add the trigger mapping to `.claude/settings.local.json`
3. Follow this template:

```markdown
# Skill: [Name]

> You are now acting as a **[Role]** with expertise in [domain].

## Your Expertise
- [Area 1]
- [Area 2]

## Your Approach
When helping with [domain] tasks:
1. [Step 1]
2. [Step 2]

## How You Communicate
- [Communication style]

## Example Tasks You Excel At
- [Task 1]
- [Task 2]
```

## Tips for Effective Skills

- **Be specific** - Vague instructions lead to vague responses
- **Include examples** - Show what good output looks like
- **Set boundaries** - Clarify what the skill should NOT do
- **Define communication style** - How should Claude respond?
- **Consider the workflow** - What questions should Claude ask first?

## Combining Skills

You can reference multiple skills in one prompt:
```
@design @frontend How should we structure the component library?
```

The hook will load both skill files, giving Claude combined expertise.
