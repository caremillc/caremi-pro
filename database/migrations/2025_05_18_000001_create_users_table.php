<?php 

class CreateUsersTable extends Migration
{
    /**
     * Run the migration
     */
    public function up(): void
    {
        /** @var Builder $schema */
        $schema = $this->schema;
        
        $schema->create('users', function (Blueprint $table) {
            // Primary key
            $table->increments('id');
            $table->string('username', 50)->nullable();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->index('email');
            $table->index('username');
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        $this->schema->dropIfExists('users');
    }
}

