# Project Management System

## Overview

This is a comprehensive project management system that implements a multi-stage approval and tracking workflow for projects. The system ensures proper planning, budget approval, and resource availability checking before project implementation.

## Workflow Stages

### 1. Project Creation (Planning Team)
Users start by creating a new project with the following information:
- **Project Name** (required)
- **Description**
- **Start Date** (required)
- **End Date** (optional)
- **Estimated Budget**
- **Priority** (Low, Medium, High, Critical)
- **Client** (optional)
- **Objectives** - Main goals of the project
- **Deliverables** - Expected outcomes

**Status:** `draft`

### 2. Budget Planning (Planning Team)
After creating a project, the planning team adds budget items:
- Item Name
- Category (Equipment, Materials, Labor, Services, Software, Other)
- Quantity
- Unit (pieces, kg, hours, etc.)
- Unit Cost
- Total Cost (auto-calculated)
- Description
- Justification

Once all budget items are added, the project is submitted for approval.

**Status:** `budget_planning` → `pending_approval`

### 3. Budget Approval (CTO & Director)
Both the CTO and Director must review and approve the project:
- View project summary and all budget items
- See total budget
- Add comments
- Approve or Reject

**Approval Rules:**
- Both CTO and Director must approve
- If either rejects, the project is rejected
- Partial approval (one approved, one pending) shows as "Partially Approved"

**Status:** `pending_approval` → `approved` or `rejected`

### 4. Item Availability Check (Implementation Team & Stores Team)
Once approved, the implementation team works with the stores team to verify item availability:
- Check available quantity for each budget item
- Set availability status:
  - **Available** - Full quantity in stock
  - **Partial** - Some quantity available
  - **Unavailable** - Need to order
  - **Ordered** - Waiting for delivery
- Add notes about availability
- Set expected availability date (if not immediately available)

**Status:** `approved` → `checking_availability` → `in_progress`

When all items are marked as "Available", the project automatically moves to "In Progress".

## Database Structure

### Projects Table
- `id` - Primary key
- `project_code` - Unique project identifier (auto-generated)
- `name` - Project name
- `description` - Project description
- `start_date` - Project start date
- `end_date` - Expected completion date
- `estimated_budget` - Initial budget estimate
- `status` - Current project status
- `priority` - Project priority level
- `created_by` - User who created the project
- `client_id` - Associated client (optional)
- `objectives` - Project goals
- `deliverables` - Expected outcomes
- `timestamps` and `soft_deletes`

### Project Budget Items Table
- `id` - Primary key
- `project_id` - Foreign key to projects
- `item_name` - Name of the item
- `description` - Item description
- `category` - Item category
- `quantity` - Required quantity
- `unit` - Unit of measurement
- `unit_cost` - Cost per unit
- `total_cost` - Total cost (auto-calculated)
- `justification` - Why this item is needed
- `status` - Approval status (pending, approved, rejected)
- `added_by` - User who added the item
- `timestamps`

### Project Approvals Table
- `id` - Primary key
- `project_id` - Foreign key to projects
- `approver_role` - Role of approver (cto, director)
- `approver_id` - User who approved/rejected
- `status` - Approval status (pending, approved, rejected)
- `comments` - Approver's comments
- `reviewed_at` - When the approval was made
- `timestamps`
- **Unique constraint:** One approval per role per project

### Project Item Availability Table
- `id` - Primary key
- `project_budget_item_id` - Foreign key to budget items
- `project_id` - Foreign key to projects
- `available_quantity` - Quantity available in stock
- `required_quantity` - Quantity needed
- `availability_status` - Status (available, partial, unavailable, ordered)
- `notes` - Additional information
- `expected_availability_date` - When item will be available
- `checked_by` - User who checked availability
- `checked_at` - When availability was checked
- `timestamps`

## Routes

| Route | Purpose |
|-------|---------|
| `/projects` | List all projects |
| `/projects/create` | Create a new project |
| `/projects/{project}/budget` | Manage project budget items |
| `/projects/{project}/approvals` | Approve/reject project budget |
| `/projects/{project}/availability` | Check item availability |

## Permissions

The system uses the following permissions:
- `view_projects` - View projects
- `create_projects` - Create new projects
- `edit_projects` - Edit existing projects
- `delete_projects` - Delete projects
- `manage_project_budget` - Add/edit budget items
- `approve_projects` - Approve/reject projects
- `check_item_availability` - Check and update item availability

## Roles

### Planning Team
- Create projects
- Manage project budgets
- View projects

### CTO
- All Planning Team permissions
- Approve/reject projects

### Director
- All Planning Team permissions
- Approve/reject projects

### Implementation Team
- View projects
- Check item availability

### Stores Team
- View projects
- Check item availability

## Installation & Setup

1. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

2. **Seed Permissions and Roles:**
   ```bash
   php artisan db:seed --class=ProjectPermissionsSeeder
   ```

3. **Assign Roles to Users:**
   Use the User Access page to assign appropriate roles to users:
   - Assign "CTO" role to the Chief Technology Officer
   - Assign "Director" role to the Director
   - Assign "Planning Team" role to project planners
   - Assign "Implementation Team" role to implementation staff
   - Assign "Stores Team" role to stores staff

## Usage Guide

### For Planning Team:

1. Navigate to **Projects** → **New Project**
2. Fill in project details and click "Create Project & Add Budget Items"
3. Add all required budget items with quantities and costs
4. Click "Submit for Approval" when done

### For CTO/Director:

1. Navigate to **Projects** and find projects with "Pending Approval" status
2. Click "Approve" to review the project
3. Review all budget items and total cost
4. Add comments (optional)
5. Click "Approve" or "Reject"

### For Implementation/Stores Team:

1. Navigate to **Projects** and find projects with "Approved" status
2. Click "Check Items" to review item availability
3. For each item, click "Check" to update availability
4. Enter available quantity and status
5. Add notes if needed
6. Set expected availability date if item is not immediately available
7. Click "Save Availability"

## Features

- **Auto-calculated totals** - Budget item totals and project totals are calculated automatically
- **Dual approval system** - Both CTO and Director must approve
- **Availability tracking** - Track which items are available and which need to be ordered
- **Progress monitoring** - Visual progress indicators for availability checks
- **Status badges** - Color-coded badges show project status at a glance
- **Filters and search** - Filter projects by status, priority, or search by name/code
- **Audit trail** - Track who created, approved, and checked items
- **Comments system** - Approvers can add comments explaining their decisions

## Project Status Flow

```
draft → budget_planning → pending_approval → approved/rejected
                                                ↓
                                          checking_availability
                                                ↓
                                           in_progress
```

## Future Enhancements

Consider adding:
- Project timeline/Gantt chart
- Budget vs actual cost tracking
- Document attachments
- Task assignment and tracking
- Email notifications for approvals
- Project completion workflow
- Reporting and analytics
- Project templates
- Budget revision workflow
- Integration with procurement system

## Support

For issues or questions, contact the system administrator or development team.
