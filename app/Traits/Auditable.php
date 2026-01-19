<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    protected static function bootAuditable()
    {
        // Log creation
        static::created(function ($model) {
            if ($model->shouldLogAudit('created')) {
                AuditLog::logActivity(
                    event: 'created',
                    auditable: $model,
                    description: $model->getAuditDescription('created'),
                    newValues: $model->getAuditableAttributes()
                );
            }
        });

        // Log updates
        static::updated(function ($model) {
            if ($model->shouldLogAudit('updated')) {
                $oldValues = [];
                $newValues = [];

                foreach ($model->getDirty() as $key => $value) {
                    if (in_array($key, $model->getAuditableColumns())) {
                        $oldValues[$key] = $model->getOriginal($key);
                        $newValues[$key] = $value;
                    }
                }

                if (!empty($newValues)) {
                    AuditLog::logActivity(
                        event: 'updated',
                        auditable: $model,
                        description: $model->getAuditDescription('updated'),
                        oldValues: $oldValues,
                        newValues: $newValues
                    );
                }
            }
        });

        // Log deletion
        static::deleted(function ($model) {
            if ($model->shouldLogAudit('deleted')) {
                AuditLog::logActivity(
                    event: 'deleted',
                    auditable: $model,
                    description: $model->getAuditDescription('deleted'),
                    oldValues: $model->getAuditableAttributes()
                );
            }
        });
    }

    /**
     * Get columns that should be audited
     */
    protected function getAuditableColumns(): array
    {
        // Allow model to define specific columns to audit
        if (property_exists($this, 'auditableColumns')) {
            return $this->auditableColumns;
        }

        // Audit all fillable columns by default, excluding sensitive ones
        $exclude = ['password', 'remember_token', 'signature_data'];
        return array_diff($this->getFillable(), $exclude);
    }

    /**
     * Get auditable attributes
     */
    protected function getAuditableAttributes(): array
    {
        $attributes = [];
        foreach ($this->getAuditableColumns() as $column) {
            $attributes[$column] = $this->getAttribute($column);
        }
        return $attributes;
    }

    /**
     * Check if audit should be logged for this event
     */
    protected function shouldLogAudit(string $event): bool
    {
        // Check if audit is disabled for this model
        if (property_exists($this, 'disableAudit') && $this->disableAudit) {
            return false;
        }

        // Check if specific events should be excluded
        if (property_exists($this, 'auditExclude') && in_array($event, $this->auditExclude)) {
            return false;
        }

        return true;
    }

    /**
     * Get human-readable description for audit log
     */
    protected function getAuditDescription(string $event): string
    {
        $modelName = class_basename($this);
        $identifier = $this->getAuditIdentifier();

        return match($event) {
            'created' => "{$modelName} '{$identifier}' was created",
            'updated' => "{$modelName} '{$identifier}' was updated",
            'deleted' => "{$modelName} '{$identifier}' was deleted",
            default => "{$modelName} '{$identifier}' was {$event}",
        };
    }

    /**
     * Get identifier for audit log (name, title, code, etc.)
     */
    protected function getAuditIdentifier(): string
    {
        // Check common identifier fields
        $identifierFields = ['name', 'title', 'code', 'email', 'id'];

        foreach ($identifierFields as $field) {
            if (isset($this->$field)) {
                return (string) $this->$field;
            }
        }

        return $this->id ?? 'Unknown';
    }

    /**
     * Get audit logs for this model
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable')->latest();
    }
}
