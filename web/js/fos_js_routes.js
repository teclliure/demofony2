fos.Router.setData({"base_url":"\/app_dev.php","routes":{"api_get_citizen_forum":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_post_citizen_forum_answers_vote":{"tokens":[["text","\/vote"],["variable","\/","[^\/]++","answer_id"],["text","\/answers"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_put_citizen_forum_answers_vote":{"tokens":[["text","\/vote"],["variable","\/","[^\/]++","answer_id"],["text","\/answers"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"PUT"},"hosttokens":[]},"api_delete_citizen_forum_answers_vote":{"tokens":[["text","\/vote"],["variable","\/","[^\/]++","answer_id"],["text","\/answers"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"api_get_citizen_forum_comments":{"tokens":[["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_get_citizen_forum_comments_childrens":{"tokens":[["text","\/childrens"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_post_citizen_forum_comments":{"tokens":[["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_put_citizen_forum_comments":{"tokens":[["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"PUT"},"hosttokens":[]},"api_post_citizen_forum_comments_like":{"tokens":[["text","\/like"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_post_citizen_forum_comments_unlike":{"tokens":[["text","\/unlike"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_delete_citizen_forum_comments_like":{"tokens":[["text","\/like"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"api_delete_citizen_forum_comments_un_like":{"tokens":[["text","\/unlike"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/citizenforums"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"api_get_processparticipation":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_post_processparticipation_answers_vote":{"tokens":[["text","\/vote"],["variable","\/","[^\/]++","answer_id"],["text","\/answers"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_put_processparticipation_answers_vote":{"tokens":[["text","\/vote"],["variable","\/","[^\/]++","answer_id"],["text","\/answers"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"PUT"},"hosttokens":[]},"api_delete_processparticipation_answers_vote":{"tokens":[["text","\/vote"],["variable","\/","[^\/]++","answer_id"],["text","\/answers"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"api_get_processparticipation_comments":{"tokens":[["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_get_processparticipation_comments_childrens":{"tokens":[["text","\/childrens"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_post_processparticipation_comments":{"tokens":[["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_put_processparticipation_comments":{"tokens":[["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"PUT"},"hosttokens":[]},"api_post_processparticipation_comments_like":{"tokens":[["text","\/like"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_post_processparticipation_comments_unlike":{"tokens":[["text","\/unlike"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_delete_processparticipation_comments_like":{"tokens":[["text","\/like"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"api_delete_processparticipation_comments_un_like":{"tokens":[["text","\/unlike"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/processparticipations"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"api_get_proposal":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_post_proposal_answers_vote":{"tokens":[["text","\/vote"],["variable","\/","[^\/]++","answer_id"],["text","\/answers"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_put_proposal_answers_vote":{"tokens":[["text","\/vote"],["variable","\/","[^\/]++","answer_id"],["text","\/answers"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"PUT"},"hosttokens":[]},"api_delete_proposal_answers_vote":{"tokens":[["text","\/vote"],["variable","\/","[^\/]++","answer_id"],["text","\/answers"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"api_get_proposal_comments":{"tokens":[["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_get_proposals_comments_childrens":{"tokens":[["text","\/childrens"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_post_proposals_comments":{"tokens":[["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_put_proposals_comments":{"tokens":[["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"PUT"},"hosttokens":[]},"api_post_proposal_comments_like":{"tokens":[["text","\/like"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_post_proposal_comments_unlike":{"tokens":[["text","\/unlike"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"POST"},"hosttokens":[]},"api_delete_proposal_comments_like":{"tokens":[["text","\/like"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"api_delete_proposal_comments_un_like":{"tokens":[["text","\/unlike"],["variable","\/","[^\/]++","comment_id"],["text","\/comments"],["variable","\/","[^\/]++","id"],["text","\/api\/v1\/proposals"]],"defaults":[],"requirements":{"_method":"DELETE"},"hosttokens":[]},"api_get_participation":{"tokens":[["text","\/api\/v1\/statistics\/participation"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"api_get_visits":{"tokens":[["text","\/api\/v1\/statistics\/visits"]],"defaults":[],"requirements":{"_method":"GET"},"hosttokens":[]},"ca__RG__fos_user_profile_public_show_comments":{"tokens":[["text","\/"],["variable","\/","[^\/]++","comments"],["text","\/comentaris"],["variable","\/","[^\/]++","username"],["text","\/perfil"]],"defaults":{"comments":"1"},"requirements":[],"hosttokens":[]},"ca__RG__fos_user_profile_public_show_proposals":{"tokens":[["text","\/"],["variable","\/","[^\/]++","proposals"],["text","\/propostes"],["variable","\/","[^\/]++","username"],["text","\/perfil"]],"defaults":{"proposals":"1"},"requirements":[],"hosttokens":[]},"comur_api_upload":{"tokens":[["text","\/upload"]],"defaults":[],"requirements":[],"hosttokens":[]},"comur_api_crop":{"tokens":[["text","\/crop"]],"defaults":[],"requirements":[],"hosttokens":[]},"comur_api_image_library":{"tokens":[["text","\/image-library"]],"defaults":[],"requirements":[],"hosttokens":[]}},"prefix":"","host":"localhost:8000","scheme":"http"});