# HTML/CSS Quest 40 Levels - Game Complete

## Overview
A comprehensive HTML/CSS learning game with 40 levels, dark theme UI, and gamified progression system.

## File
- **htmlcss_quest.html** (56 KB) - Single-file game, no dependencies required

## Features Implemented

### Game Mechanics
- ✓ 40 levels across 7 zones
- ✓ XP/Level progression system (100 XP per level)
- ✓ 3-tier hint system (Hint 1: Free, Hint 2: -10 XP, Hint 3: -25 XP)
- ✓ Stage map with level badges (locked/current/completed)
- ✓ Victory modal with XP rewards
- ✓ Auto-save to localStorage (`htmlcssquest_40` key)
- ✓ Responsive design (mobile-friendly)

### Content Structure
- **Zone 1** (Levels 1-6): HTML Basics
  - h1-h6 + p, links, images, lists, text formatting, choice review
  
- **Zone 2** (Levels 7-12): HTML Forms & Tables
  - Forms, input types, textarea/button, tables, div/span/class/id, choice review
  
- **Zone 3** (Levels 13-18): CSS Basics
  - Colors, fonts, margins/padding, borders, width/height/display, choice review
  
- **Zone 4** (Levels 19-24): CSS Layout
  - Position, float/clear, flexbox basics, flexbox advanced, CSS grid, choice review
  
- **Zone 5** (Levels 25-30): Responsive Design
  - Media queries, viewport, rem/em units, responsive images, responsive nav, choice review
  
- **Zone 6** (Levels 31-36): Advanced CSS
  - Transitions, animations, transforms, pseudo-elements, CSS variables, choice review
  
- **Zone 7** (Levels 37-40): Projects & Boss
  - Card components, navigation bars, hero sections, final boss challenge

### UI/UX
- ✓ Dark theme (#0f0e17 bg, #1a1932 cards)
- ✓ Animated particles background
- ✓ Sticky top bar with XP/Level display
- ✓ Split-view editor (left: code + tutorial, right: preview + challenge)
- ✓ Syntax-highlighted code editor
- ✓ Live iframe preview for HTML/CSS rendering
- ✓ Tutorial box with code examples
- ✓ Choice questions with 4 options and feedback
- ✓ Status messages (success/error)
- ✓ 3-level hints display
- ✓ Smooth animations and transitions

### Game Logic
- ✓ Code validation using `codeCheck` JS function (string-based)
- ✓ DOM validation using `validate` JS function (checks iframe elements)
- ✓ Choice question answer checking with explanations
- ✓ XP deduction for hints (tier 2: -10, tier 3: -25)
- ✓ Level unlocking system (sequential progression)
- ✓ Multiple choice questions at zone reviews (6 choice levels)

## How to Play

1. **Open the file** in any modern web browser
2. **Read the tutorial** in the left panel - shows concepts and code examples
3. **Write HTML/CSS** in the code editor
4. **Click Preview** to render your code in the iframe
5. **Click Check** to validate your solution
6. **Use Hints** if stuck (costs XP for tiers 2-3)
7. **Complete 40 levels** to become an HTML/CSS master!

## Technical Details

### Data Structure
Each level contains:
- `zone` - Zone number (1-7)
- `level` - Level number (1-40)
- `title` - Level title in Thai
- `tutorialTitle` - Tutorial heading
- `tutorial` - Tutorial content
- `exampleCode` - Code example
- `challenge` - Challenge description
- `hints` - Array of 3 hints
- `codeCheck` - JS function to validate code syntax
- `validate` - JS function to validate DOM in iframe
- `type` - "standard" or "choice"
- `choices` - (choice only) Array of {text, correct, explanation}

### Validation System
- **codeCheck**: Tests if required HTML/CSS syntax is present (e.g., `code.includes('<h1')`)
- **validate**: Tests DOM elements in iframe (e.g., `doc.querySelector('h1')`)
- Both must pass for level to complete

### Scoring System
- Base XP per level: 100
- Hint costs: Tier 1 (free), Tier 2 (-10 XP), Tier 3 (-25 XP)
- Max possible: 4000 XP (40 levels × 100)
- Levels = Math.floor(totalXP / 400)

## Browser Compatibility
- Chrome/Edge 90+
- Firefox 88+
- Safari 14+
- All modern browsers with ES6 support

## All Content in Thai
- All instructions, challenges, hints, and feedback messages are in Thai
- Code examples follow HTML/CSS standard syntax
- No external dependencies required

## Statistics
- **Total size**: 56 KB (single file)
- **Levels**: 40
- **Choice questions**: 6
- **Hints total**: 120 (3 per level)
- **Code examples**: 40
- **Lines of HTML**: 424

