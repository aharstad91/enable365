# Setup Prompt for New Projects

Copy this prompt to a new Claude Code project to set up the same workflow:

---

## Prompt:

```
I want to set up a structured workflow for this project with session logging and context persistence between sessions.

Create the following files:

1. **CLAUDE.md** in project root - Main instructions with:
   - Project overview (explore the codebase first)
   - Architecture and folder structure
   - Important files and functions
   - Tech stack
   - Common tasks

2. **_claude/** folder with workflow files:
   - **session-log.md** - Session log with "Last Updated", "Active Context", "Next Steps", changelog
   - **instructions.md** - Workflow instructions for Claude Code
   - **feature-request-guide.md** - Template for feature requests
   - **setup-prompt.md** - This setup guide (for reference)
   - **skills/** - Folder with skill files (see Skills section below)

3. **.claude/settings.local.json** - Hooks for context and skills (see Hooks section below)

Start by exploring the project, then create the files with content tailored to this codebase.
```

---

## Hooks Configuration

The `.claude/settings.local.json` file configures automatic context loading and skill triggers.

### Basic Setup (Context Only)

```json
{
  "hooks": {
    "UserPromptSubmit": [
      {
        "matcher": "",
        "hooks": [
          {
            "type": "command",
            "command": "cat _claude/session-log.md _claude/instructions.md 2>/dev/null || true"
          }
        ]
      }
    ]
  }
}
```

### Full Setup (Context + Skills)

```json
{
  "hooks": {
    "UserPromptSubmit": [
      {
        "matcher": "",
        "hooks": [
          {
            "type": "command",
            "command": "cat _claude/session-log.md _claude/instructions.md 2>/dev/null || true"
          }
        ]
      },
      {
        "matcher": "@design",
        "hooks": [
          {
            "type": "command",
            "command": "cat _claude/skills/designer.md 2>/dev/null || true"
          }
        ]
      },
      {
        "matcher": "@backend",
        "hooks": [
          {
            "type": "command",
            "command": "cat _claude/skills/backend.md 2>/dev/null || true"
          }
        ]
      },
      {
        "matcher": "@frontend",
        "hooks": [
          {
            "type": "command",
            "command": "cat _claude/skills/frontend.md 2>/dev/null || true"
          }
        ]
      },
      {
        "matcher": "@review",
        "hooks": [
          {
            "type": "command",
            "command": "cat _claude/skills/reviewer.md 2>/dev/null || true"
          }
        ]
      }
    ]
  }
}
```

### How Skills Work

When you type `@design` in a prompt, the hook matches and loads `skills/designer.md`, giving Claude specific expertise and a persona. You can combine multiple skills:

```
@design @frontend How should we structure the component library?
```

### Adding New Skills

1. Create a new file in `_claude/skills/` (e.g., `devops.md`)
2. Add a new matcher to `.claude/settings.local.json`:
```json
{
  "matcher": "@devops",
  "hooks": [
    {
      "type": "command",
      "command": "cat _claude/skills/devops.md 2>/dev/null || true"
    }
  ]
}
```

---

## Skills

Skills give Claude specific expertise when triggered with `@skillname`. See `_claude/skills/README.md` for full documentation.

### Included Skills

| Trigger | File | Description |
|---------|------|-------------|
| `@design` | `designer.md` | UI/UX design expertise |
| `@backend` | `backend.md` | Server-side architecture |
| `@frontend` | `frontend.md` | Modern web development |
| `@review` | `reviewer.md` | Code review best practices |

### Skill File Template

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

---

## MCP Servers

Add these MCP servers for extended functionality:

### GitHub (global - recommended)
```bash
claude mcp add github -s user -- npx -y @modelcontextprotocol/server-github
```
Requires `GITHUB_PERSONAL_ACCESS_TOKEN` environment variable. Add to `~/.claude/settings.json`:
```json
{
  "mcpServers": {
    "github": {
      "command": "/opt/homebrew/bin/npx",
      "args": ["-y", "@modelcontextprotocol/server-github"],
      "env": {
        "GITHUB_PERSONAL_ACCESS_TOKEN": "your_token_here"
      }
    }
  }
}
```

### Stripe (global - recommended for payment projects)
```bash
claude mcp add stripe -s user -- npx -y @stripe/mcp --tools=all --api-key=sk_test_xxx
```
Or add to `~/.claude/settings.json`:
```json
{
  "mcpServers": {
    "stripe": {
      "command": "npx",
      "args": ["-y", "@stripe/mcp", "--tools=all", "--api-key=sk_test_xxx"]
    }
  }
}
```

### Trello (project-specific)
```bash
claude mcp add trello -s project -- npx -y @delorenj/mcp-server-trello
```
Add API keys to `.mcp.json`:
```json
{
  "mcpServers": {
    "trello": {
      "type": "stdio",
      "command": "npx",
      "args": ["-y", "@delorenj/mcp-server-trello"],
      "env": {
        "TRELLO_API_KEY": "your_api_key",
        "TRELLO_TOKEN": "your_token"
      }
    }
  }
}
```
Get API key: https://trello.com/app-key
Get token: https://trello.com/1/authorize?expiration=never&scope=read,write&response_type=token&key=YOUR_API_KEY

### Chrome DevTools (project-specific)
```bash
claude mcp add chrome-devtools -s project -- npx -y chrome-devtools-mcp@latest
```
Provides access to browser automation, DOM inspection, network requests, and console messages.

### Figma Desktop (project-specific)
Requires Figma Desktop app with MCP plugin enabled.
```json
{
  "mcpServers": {
    "figma-desktop": {
      "type": "http",
      "url": "http://127.0.0.1:3845/mcp"
    }
  }
}
```

### Complete `.mcp.json` Example
```json
{
  "mcpServers": {
    "chrome-devtools": {
      "type": "stdio",
      "command": "npx",
      "args": ["-y", "chrome-devtools-mcp@latest"],
      "env": {}
    },
    "figma-desktop": {
      "type": "http",
      "url": "http://127.0.0.1:3845/mcp"
    },
    "trello": {
      "type": "stdio",
      "command": "npx",
      "args": ["-y", "@delorenj/mcp-server-trello"],
      "env": {
        "TRELLO_API_KEY": "your_api_key",
        "TRELLO_TOKEN": "your_token"
      }
    }
  }
}
```

---

## Security

Add to `.gitignore`:
```gitignore
# MCP configuration (contains API keys)
.mcp.json
/.mcp.json

# Claude local settings (may contain sensitive data)
.claude/settings.local.json
```

**Important:**
- `.mcp.json` contains API keys (Trello, Stripe, etc.) - NEVER commit to git
- `.claude/settings.local.json` may contain sensitive hooks - consider whether to commit
- Global MCP servers (`-s user`) are stored in `~/.claude/settings.json` - outside repo
- Use environment variables or secrets manager for production
- Rotate API keys regularly, especially if accidentally committed

---

## Notes

- Adjust the `cat` command in hooks if files are in a subfolder (e.g., `wp-content/`)
- The context hook runs on every prompt; skill hooks only when matched
- MCP servers are project-specific (stored in `.mcp.json`) unless you use `-s user` for global
- Run `/mcp` in Claude Code to see status of all MCP servers
- Restart Claude Code after adding new MCP servers or modifying hooks

---

## File Structure After Setup

```
project/
├── .claude/
│   └── settings.local.json    # Hooks (context + skills)
├── .mcp.json                   # MCP servers (gitignored)
├── .gitignore                  # Includes .mcp.json
├── CLAUDE.md                   # Project documentation (use template)
└── _claude/                    # Claude workflow files
    ├── CLAUDE.md               # Template for project documentation
    ├── session-log.md          # Session log
    ├── instructions.md         # Workflow instructions
    ├── feature-request-guide.md # Feature request template
    ├── setup-prompt.md         # This file
    └── skills/                 # Skill files
        ├── README.md           # Skills documentation
        ├── designer.md         # @design
        ├── backend.md          # @backend
        ├── frontend.md         # @frontend
        └── reviewer.md         # @review
```

**Note:** Copy `_claude/CLAUDE.md` to the project root and fill in the project-specific details.

For monorepo/nested structure (files in subfolder):
```
project/
├── .claude/
│   └── settings.local.json
├── .mcp.json
├── CLAUDE.md
└── src/                        # Or any subfolder
    └── _claude/
        ├── session-log.md
        ├── instructions.md
        ├── feature-request-guide.md
        ├── setup-prompt.md
        └── skills/
            └── ...
```
