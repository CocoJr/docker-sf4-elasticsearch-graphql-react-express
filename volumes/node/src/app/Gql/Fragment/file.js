/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import gql from "graphql-tag";

export let FileFragment = gql`
    fragment FileFragment on File {
        id
        publicPath
    }
`;
