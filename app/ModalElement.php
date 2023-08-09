<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// kinda confusingly this isn't anything like a ButtonElement etc - it's a container which may have elements inside

// This is very similar to Interaction and reuses some of the same element types
class ModalElement extends Model
{
    protected $fillable = ['modal_id', 'element_type', 'element_id'];

    public function element() {
        return $this->morphTo();
    }

    public function modal() {
        return $this->belongsTo(Modal::class, 'modal_id');
    }

    public $allowedElementTypes = [
        ButtonElement::class,
        HotspotElement::class,
        ImageElement::class
    ];

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
