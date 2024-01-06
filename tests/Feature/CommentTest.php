<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{

    public function testCreateComment(): void
    {
        $comment = new Comment();
        $comment->email = "palakau";
        $comment->title = "Sample title";
        $comment->comment = "Sample Comment";
        $comment->save();

        self::assertNotNull($comment->id);
    }

    public function testDefaultAttributeValues(): void
    {
        //value dari title dan comment dari default value di model
        $comment = new Comment();
        $comment->email = "palakau";
        $comment->save();

        self::assertNotNull($comment->id);
        self::assertNotNull($comment->title);
        self::assertNotNull($comment->comment);
    }



}
