---
name: Structure & Flow
colors:
  surface: '#f9f9f7'
  surface-dim: '#d9dad8'
  surface-bright: '#f9f9f7'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f4f2'
  surface-container: '#edeeec'
  surface-container-high: '#e8e8e6'
  surface-container-highest: '#e2e3e1'
  on-surface: '#1a1c1b'
  on-surface-variant: '#404846'
  inverse-surface: '#2f3130'
  inverse-on-surface: '#f0f1ef'
  outline: '#717975'
  outline-variant: '#c0c8c4'
  surface-tint: '#3b665b'
  primary: '#002a22'
  on-primary: '#ffffff'
  primary-container: '#134137'
  on-primary-container: '#80ada0'
  inverse-primary: '#a2d0c2'
  secondary: '#805600'
  on-secondary: '#ffffff'
  secondary-container: '#ffc569'
  on-secondary-container: '#775000'
  tertiary: '#21260f'
  on-tertiary: '#ffffff'
  tertiary-container: '#373c22'
  on-tertiary-container: '#a1a785'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#bdecde'
  primary-fixed-dim: '#a2d0c2'
  on-primary-fixed: '#00201a'
  on-primary-fixed-variant: '#224e44'
  secondary-fixed: '#ffddaf'
  secondary-fixed-dim: '#f6bd61'
  on-secondary-fixed: '#281800'
  on-secondary-fixed-variant: '#614000'
  tertiary-fixed: '#e0e6c1'
  tertiary-fixed-dim: '#c4caa7'
  on-tertiary-fixed: '#191e07'
  on-tertiary-fixed-variant: '#44492e'
  background: '#f9f9f7'
  on-background: '#1a1c1b'
  surface-variant: '#e2e3e1'
  slate-gray: '#537E83'
  neutral-bg: '#F8F9FA'
  success-green: '#2D6A4F'
  warning-amber: '#D97706'
  danger-red: '#991B1B'
typography:
  display-lg:
    fontFamily: Hanken Grotesk
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Hanken Grotesk
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
  headline-md:
    fontFamily: Hanken Grotesk
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: JetBrains Mono
    fontSize: 13px
    fontWeight: '500'
    lineHeight: 16px
  label-sm:
    fontFamily: JetBrains Mono
    fontSize: 11px
    fontWeight: '500'
    lineHeight: 14px
  headline-lg-mobile:
    fontFamily: Hanken Grotesk
    fontSize: 28px
    fontWeight: '600'
    lineHeight: 36px
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 4px
  container-margin: 32px
  gutter: 24px
  section-gap: 48px
  form-element-gap: 16px
---

## Brand & Style

The design system is engineered for a high-utility enterprise environment focusing on asset lifecycle management. It prioritizes clarity, accountability, and professional rigor. The visual direction is **Corporate / Modern**, characterized by a highly structured interface that manages dense data without overwhelming the user.

The aesthetic utilizes a grounded, earthy palette to evoke stability and institutional trust. It balances the "industrial" nature of inventory management with a sophisticated digital execution, ensuring that complex workflows like multi-party digital signatures and capital threshold validations feel seamless and authoritative.

Key principles:
- **Functional Density:** Optimizing information architecture for asset tables and audit logs.
- **Process Integrity:** Using visual cues to indicate stages of the mutation lifecycle (Draft vs. Archived).
- **Institutional Trust:** A clean, no-nonsense interface that reflects the financial and logistical accuracy required for asset auditing.

## Colors

The palette is derived from the WBI corporate identity, utilizing **Deep Teal** as the primary anchor for navigation and primary actions to establish a serious, professional tone. **Earthy Gold** serves as the secondary accent, reserved for highlighting "Mutation" status, pending actions, and financial threshold indicators.

- **Primary (Deep Teal):** Used for top navigation, primary buttons, and active state indicators.
- **Secondary (Earthy Gold):** Used for highlighting specialized workflows and "attention-required" states.
- **Tertiary (Olive Drab):** Used for secondary UI elements like tags, categories, and background accents for specific departments.
- **Slate Gray:** Utilized for data visualization and non-interactive iconography.
- **Neutral Backgrounds:** A clean, off-white surface is used to reduce eye strain during long periods of data entry and auditing.

## Typography

This design system uses a tri-font approach to maximize legibility across different data types:
1. **Hanken Grotesk (Headlines):** A sharp, contemporary grotesque that provides a modern corporate feel for page titles and section headers.
2. **Inter (Body):** The workhorse for all interface text, chosen for its exceptional legibility in dense forms and tables.
3. **JetBrains Mono (Labels/Codes):** Specifically assigned to **Asset Codes** (e.g., `AST/HE/08/2024/001`) and system-generated IDs to ensure characters like '0' and 'O' or '1' and 'l' are easily distinguishable for auditors.

Line heights are generous in body text to prevent fatigue, while labels use a tighter leading to fit within compact status badges and table cells.

## Layout & Spacing

The design system employs a **Fixed Grid** layout for desktop (1280px max-width) to maintain consistent scanning paths for data-heavy tables. On mobile devices, the layout transitions to a fluid, single-column model to accommodate the digital signature canvas and on-site physical asset verification.

- **Grid:** 12-column system with 24px gutters.
- **Sidebars:** Fixed 260px navigation on the left for quick access to "Gudang Inventaris," "Arsip Form," and "Audit Logs."
- **Data Density:** Use a compact spacing model for tables (8px vertical padding in cells) to maximize visible rows, while using a more spacious model (24px - 32px) for form-based workflows like "Mutation Request" to reduce cognitive load.

## Elevation & Depth

To maintain a clean corporate look, the design system avoids heavy shadows, opting instead for **Tonal Layers** and **Low-Contrast Outlines**.

- **Surface Tiers:** The main background uses the `neutral-bg`. White containers (`#FFFFFF`) are placed on top with a subtle `1px` border (`#E5E7EB`) to define work areas.
- **Depth:** Only "Active" elements (like a focused input or an open modal) receive a soft, ambient shadow (8px blur, 4% opacity, tinted with Deep Teal) to pull them forward.
- **Status Planes:** Form headers use a subtle top-border color-coded to the asset status (e.g., a green top-border for "Active" assets, amber for "Waiting Receiver").

## Shapes

The design system uses a **Soft (0.25rem / 4px)** roundedness. This minimal rounding maintains a disciplined, professional appearance while avoiding the harshness of sharp 90-degree corners. 

- **Standard Elements:** Inputs, Buttons, and Cards use the 4px base radius.
- **Large Components:** Modals and main content containers use the `rounded-lg` (8px) for a slightly softer container feel.
- **Badges:** Status badges for asset conditions (e.g., "Good," "Damaged") use the `rounded-xl` (12px) to distinguish them from interactive buttons.

## Components

### Buttons
- **Primary:** Solid Deep Teal with white text. Used for "Execute Mutation" or "Save Asset."
- **Secondary:** Outlined Deep Teal. Used for "Download PDF" or "Add Item."
- **Disabled State:** For the `< Rp 500.000` business rule, the button must be visually greyed out with a tooltip explaining the capitalization threshold.

### Digital Signature Canvas
- A dedicated white surface with a "Signature Required" placeholder and a clear-button in the Earthy Gold accent. Must include a timestamp metadata label below the canvas.

### Asset Cards & Tables
- **Tables:** Must include sticky headers. Row hover states should use a light tint of Teal (`#F0F4F3`).
- **Audit Timeline:** A vertical line component using Slate Gray, with dot indicators for every mutation event, linked to the specific "Arsip Form" PDF.

### Input Fields
- Formats for "Purchase Price" should include a leading "Rp" prefix as a fixed addon. 
- Validation states must be clear; inputs for values below 500,000 should trigger an immediate "Non-Asset" warning state.

### Chips/Badges
- Used for Asset Status: `in_storage` (Slate), `active` (Green), `under_repair` (Amber), `disposed` (Red). All chips use the `label-sm` JetBrains Mono font for a technical look.