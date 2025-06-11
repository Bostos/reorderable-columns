# Changelog

All notable changes to `filament-reorderable-columns` will be documented in this file.

## Filament reorderable columns - 2025-06-11

**Filament Reorderable Columns** is [Filament](https://filamentphp.com/) plugin that allows users to reorder table columns via drag-and-drop. The new column order can be saved either in the session or persisted in the database (per user).


---

### Features

- **Intuitive Drag & Drop:** Easily reorder table columns to create your preferred layout
- **Persistent Ordering:** Column order is saved and automatically reapplied on next visit
- **Flexible Storage Drivers:**
  - **Database:** Persist layouts per-user, so everyone gets their own custom view
  - **Session:** Keep the layout for the current session, resetting on logout
  
- **Seamless Integration:** Designed to feel like a native Filament feature
- **Smart Column Handling:**
  - Remembers the order of visible columns
  - Automatically places newly added columns at the end of the table
  


---

## 1.0.0 - 202X-XX-XX

- initial release
