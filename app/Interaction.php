<?php

namespace App;

use App\Repositories\ElementRepository;
use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Interaction extends Model
{
    protected $guarded = ['id', 'nodeId'];

    protected static function booted()
    {
        // This adds the ->where('user_id', userId) to
        // all queries
        static::addGlobalScope(new UserScope());

        static::deleting(function ($interaction) {
            // Delete all interaction related element items
            $elementRepo = app()->make(ElementRepository::class);

            $elementRepo->delete($interaction->element_type, $interaction->element_id);
        });

        // This adds the user_id = authenticated user to
        // the model on creation
        static::creating(function ($interaction) {
            $interaction->user_id = ( auth()->user()->parent_user_id ) ? auth()->user()->parent_user_id : auth()->user()->id;
        });
    }


    public function element() : MorphTo
    {
        return $this->morphTo();
    }

    public $allowedElementTypes = [
        ButtonElement::class,
        HotspotElement::class,
        ImageElement::class,
        TextElement::class,
        CustomHtmlElement::class,
        TriggerElement::class,
        FormElement::class
    ];

    public function elementGroup()
    {
        return $this->belongsTo('App\ElementGroup');
    }

    public static function getInteractionByInfo($type, $id)
    {
        return self::query()
                    ->where([['element_type', '=', $type], ['element_id', '=', $id]])
                    ->first();
    }

    public static function getTotalInteractionsForNode($nodeId, $elementType)
    {
        return self::query()
                    ->where([
                        ['node_id', $nodeId],
                        ['element_type', $elementType]
                    ])->get()
                    ->count();
    }

    /**
     * get interaction timeIn
     * @return false|float
     */
    public function getTimeIn()
    {
        return round($this->timeIn, 4);
    }

    /**
     * get interaction timeOut
     * @return false|float
     */
    public function getTimeOut()
    {
        return round($this->timeOut, 4);
    }

    /**
     * get interaction zIndex
     * @return mixed
     */
    public function getElementZindex()
    {
        return $this->element->zIndex ?? 0;
    }


    /**
     * The following methods are workarounds for GraphQL's issues with Polymorphism
     */
    public function ButtonElement() : BelongsTo
    {
        return $this->belongsTo(ButtonElement::class, "element_id", "id");
    }
    public function HotspotElement() : BelongsTo
    {
        return $this->belongsTo(HotspotElement::class, "element_id", "id");
    }
    public function ImageElement() : BelongsTo
    {
        return $this->belongsTo(ImageElement::class, "element_id", "id");
    }
    public function TextElement() : BelongsTo
    {
        return $this->belongsTo(TextElement::class, "element_id", "id");
    }
    public function CustomHtmlElement() : BelongsTo
    {
        return $this->belongsTo(CustomHtmlElement::class, "element_id", "id");
    }
    public function TriggerElement() : BelongsTo
    {
        return $this->belongsTo(TriggerElement::class, "element_id", "id");
    }
    public function FormElement() : BelongsTo
    {
        return $this->belongsTo(FormElement::class, "element_id", "id");
    }
}
